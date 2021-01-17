import * as THREE from '/3d/src/build/three.module.js';

		import Stats from '/3d/src/jsm/libs/stats.module.js';
		import { GUI } from '/3d/src/jsm/libs/dat.gui.module.js';

		import { OrbitControls } from '/3d/src/jsm/controls/OrbitControls.js';
		import { GLTFLoader } from '/3d/src/jsm/loaders/GLTFLoader.js';
		import { DecalGeometry } from '/3d/src/jsm/geometries/DecalGeometry.js';

		var container = document.getElementById( 'container' );
		var area = document.getElementsByClassName('3d');

		var renderer, scene, camera, stats;
		var mesh;
		var raycaster;
		var line;

		var intersection = {
			intersects: false,
			point: new THREE.Vector3(),
			normal: new THREE.Vector3()
		};
		var mouse = new THREE.Vector2();
		var intersects = [];

		var textureLoader = new THREE.TextureLoader();
		var decalDiffuse = textureLoader.load( '/3d/src/textures/decal/decal-diffuse.png' );
		var decalNormal = textureLoader.load( '/3d/src/textures/decal/decal-normal.jpg' );

		var decalMaterial = new THREE.MeshPhongMaterial( {
			specular: 0x444444,
			map: decalDiffuse,
			normalMap: decalNormal,
			normalScale: new THREE.Vector2( 1, 1 ),
			shininess: 30,
			transparent: true,
			depthTest: true,
			depthWrite: false,
			polygonOffset: true,
			polygonOffsetFactor: - 4,
			wireframe: false
		} );

		var decals = [];
		var mouseHelper;
		var position = new THREE.Vector3();
		var orientation = new THREE.Euler();
		var size = new THREE.Vector3( 10, 10, 10 );

		var params = {
			minScale: 1,
			maxScale: 5,
			rotate: true,
			clear: function () {

				removeDecals();

			}
		};

		window.addEventListener( 'load', init );

		function init() {

			renderer = new THREE.WebGLRenderer( { antialias: true } );
			renderer.setPixelRatio( window.devicePixelRatio );

			renderer.setSize( $('.3d').width(), $('.3d').height() );
			container.appendChild( renderer.domElement );

			stats = new Stats();
			container.appendChild( stats.dom );

			scene = new THREE.Scene();

			camera = new THREE.PerspectiveCamera( 45, $('.3d').width() / $('.3d').height(), 1, 1000 );
			camera.position.z = 80;
			camera.target = new THREE.Vector3();

			var controls = new OrbitControls( camera, renderer.domElement );
			controls.minDistance = 10;
			controls.maxDistance = 200;

			scene.add( new THREE.AmbientLight( 0x443333 ) );

			var light = new THREE.DirectionalLight( 0xffddcc, 1 );
			light.position.set( 1, 0.75, 0.5 );
			scene.add( light );

			var light = new THREE.DirectionalLight( 0xccccff, 1 );
			light.position.set( - 1, 0.75, - 0.5 );
			scene.add( light );

			var geometry = new THREE.BufferGeometry();
			geometry.setFromPoints( [ new THREE.Vector3(), new THREE.Vector3() ] );

			line = new THREE.Line( geometry, new THREE.LineBasicMaterial() );
			scene.add( line );

			loadLeePerrySmith();

			raycaster = new THREE.Raycaster();

			mouseHelper = new THREE.Mesh( new THREE.BoxBufferGeometry( 1, 1, 10 ), new THREE.MeshNormalMaterial() );
			mouseHelper.visible = false;
			scene.add( mouseHelper );

			window.addEventListener( 'resize', onWindowResize, false );

			var moved = false;
			var screenshot_3d = "";
			var resident = $('.resident').val();

			controls.addEventListener( 'change', function () {

				moved = true;

			} );

			window.addEventListener( 'pointerdown', function () {

				moved = false;

			}, false );

			window.addEventListener( 'pointerup', function ( event ) {

				if ( moved === false ) {
					switch (event.which) {
				        case 1:
				        	checkIntersection( event.clientX, event.clientY );

							if ( intersection.intersects ) shoot();

				            break;
				        case 2:
				            // alert('Middle mouse button pressed');
				            break;
				        case 3:
				            // alert('Right mouse button pressed');
				            takeScreenshot();
				            break;
				        default:
				            // alert('You have a strange mouse');
				    }
				}

			} );

			window.oncontextmenu = function ()
			{
			    return false;     // cancel default menu
			}

			function ShowCommentModal() {
				jQuery('#commentsModal').modal('show', {
                    backdrop: 'static'
                });

                jQuery.ajax({
                    url: "/getbodyharmcomments",
                    success: function(response) {
                    	$('.comment').empty();
                    	
                    	var element = "";
                    	if (response) {
                    		for (var i = 0; i < response.length; i++) {
                    			element += "<option value=" + response[i]['id'] + ">" + response[i]['name'] + "</option>";
                    		}
                    	}
                        jQuery('#commentsModal .comment').append(element);
                    }
                });
			}

			// add Screenshot listener
    		document.getElementById("takeScreenshot").addEventListener('click', takeScreenshot);
    		document.getElementById("shot").addEventListener('click', SubmitHarm);

			window.addEventListener( 'pointermove', onPointerMove );

			function onPointerMove( event ) {

				if ( event.isPrimary ) {

					checkIntersection( event.clientX, event.clientY );

				}

			}

			function checkIntersection( x, y ) {

				if ( mesh === undefined ) return;

				mouse.x = ( x / window.innerWidth ) * 2 - 1;
				mouse.y = - ( y / window.innerHeight ) * 2 + 1;

				raycaster.setFromCamera( mouse, camera );
				raycaster.intersectObject( mesh, true, intersects );

				if ( intersects.length > 0 ) {

					var p = intersects[ 0 ].point;
					mouseHelper.position.copy( p );
					intersection.point.copy( p );

					var n = intersects[ 0 ].face.normal.clone();
					n.transformDirection( mesh.matrixWorld );
					n.multiplyScalar( 10 );
					n.add( intersects[ 0 ].point );

					intersection.normal.copy( intersects[ 0 ].face.normal );
					mouseHelper.lookAt( n );

					var positions = line.geometry.attributes.position;
					positions.setXYZ( 0, p.x, p.y, p.z );
					positions.setXYZ( 1, n.x, n.y, n.z );
					positions.needsUpdate = true;

					intersection.intersects = true;

					intersects.length = 0;

				} else {

					intersection.intersects = false;

				}

			}

			onWindowResize();
			animate();

			function takeScreenshot() {
				// download file like this.
			    var a = document.createElement('a');
			    // Without 'preserveDrawingBuffer' set to true, we must render now
			    renderer.render(scene, camera);
			    a.href = renderer.domElement.toDataURL().replace("image/png", "image/jpeg");
			    screenshot_3d = a.href;

			    ShowCommentModal();

			    //download as image
			    // a.download = 'canvas.jpeg';
			    // a.click();
			}

			function SubmitHarm() {
				var formData = new FormData();
				formData.append("resident", resident);
				formData.append("comment", $('.comment').val());
				formData.append("screenshot_3d", screenshot_3d);

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "/storeStorage",
					type: 'POST',
					contentType: false,
				    cache: false,
				    processData: false,
					data: formData,
					success: function(result, status) {
						window.location.href = result;
					}
				});
			}
		}

		function loadLeePerrySmith() {

			var loader = new GLTFLoader();

			loader.load( '/3d/src/models/gltf/whole/123.glb', function ( gltf ) {

				mesh = gltf.scene.children[ 0 ].children[0];
				scene.add( mesh );
				mesh.scale.set( 10, 10, 10 );
			} );

		}

		function shoot() {

			position.copy( intersection.point );
			orientation.copy( mouseHelper.rotation );

			if ( params.rotate ) orientation.z = Math.random() * 2 * Math.PI;

			var scale = params.minScale + 0.45 * ( params.maxScale - params.minScale );
			size.set( scale, scale, scale );

			var material = decalMaterial.clone();
			material.color.setHex( 0xdc1c1c );

			var m = new THREE.Mesh( new DecalGeometry( mesh, position, orientation, size ), material );

			decals.push( m );
			scene.add( m );

		}

		function removeDecals() {

			decals.forEach( function ( d ) {

				scene.remove( d );

			} );

			decals = [];

		}

		function onWindowResize() {

			camera.aspect = $('.3d').width() / $('.3d').height();
			camera.updateProjectionMatrix();

			renderer.setSize( $('.3d').width(), $('.3d').height() );

		}

		function animate() {

			requestAnimationFrame( animate );

			renderer.render( scene, camera );

			stats.update();

		}