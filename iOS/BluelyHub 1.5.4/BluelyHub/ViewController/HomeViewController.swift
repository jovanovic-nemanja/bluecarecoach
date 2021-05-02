//
//  HomeViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 2/1/21.
//

import UIKit
import AVKit
import SwiftyJSON

class HomeViewController: UIViewController, AVPlayerViewControllerDelegate, UITableViewDelegate, UITableViewDataSource {
    @IBOutlet weak var videoFrame: UIView!
    @IBOutlet weak var tableView: UITableView!
    @IBOutlet weak var labelTagline: UILabel!
    
    var video: Video?
    var player = AVPlayer()
    let vcPlayer = AVPlayerViewController()
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)

        if player.currentItem == nil {
            getVideoLink()
        } else {
            player.play()
        }
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if player.currentItem != nil {
            player.pause()
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        tableView.tableFooterView = UIView()
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        
        NotificationCenter.default.removeObserver(NSNotification.Name.AVPlayerItemDidPlayToEndTime)
    }
    
    func getVideoLink() {
        UIManager.shared.showHUD(view: self.view)
        
        let userID = DataManager.currentUser?.id
        var params: JSON = JSON()
        params["userid"].int = userID
        
        APIManager.shared.getVideo(params) { (success, video, message) in
            UIManager.shared.hideHUD()
            if success {
                self.video = video!
                self.tableView.reloadData()
                if let videoLink = self.video!.link {
                    self.playVideo(url: videoLink)
                }
                
                self.labelTagline.text = self.video?.tagline
            }
        }
    }
    
    func playVideo(url :String) {
        print(url)
        player = AVPlayer(url: URL(string: url)!)
        vcPlayer.showsPlaybackControls = false
        vcPlayer.player = player
        self.addChild(vcPlayer)
        vcPlayer.view.frame = videoFrame.frame
        self.view.addSubview(vcPlayer.view)
        player.play()
        vcPlayer.delegate = self
        NotificationCenter.default.addObserver(self, selector: #selector(playerDidFinishPlaying), name: NSNotification.Name.AVPlayerItemDidPlayToEndTime, object: vcPlayer.player?.currentItem)
    }
    
    @objc func playerDidFinishPlaying(note: NSNotification) {
        if let playerItem = note.object as? AVPlayerItem {
            playerItem.seek(to: CMTime.zero, completionHandler: nil)
            player.play()
        }
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        if (self.vcPlayer.isBeingDismissed) {
            self.vcPlayer.removeObserver(self, forKeyPath: #keyPath(UIViewController.view.frame))
            self.performSegue(withIdentifier: "home", sender: nil)
        }
    }
    
    // MARK: UITableViewDelegate, UITableViewDataSource
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return 3
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        self.tabBarController?.selectedIndex = 1
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "Cell")
        if cell == nil {
            cell = UITableViewCell(style: .value1, reuseIdentifier: "Cell")
        }
        
        cell?.selectionStyle = .none

        switch indexPath.row {
            case 0:
                cell?.imageView?.image = UIImage(systemName: "arrow.up.doc.on.clipboard")
                cell?.imageView?.tintColor = .systemBlue
                cell?.textLabel?.text = "Uploaded Credentials"
                if let video = self.video {
                    cell?.detailTextLabel?.text = String(video.all_uploaded_credentials_count)
                }
            case 1:
                cell?.imageView?.image = UIImage(systemName: "arrow.triangle.2.circlepath.doc.on.clipboard")
                cell?.imageView?.tintColor = .systemYellow
                cell?.textLabel?.text = "Expired Credentials"
                if let video = self.video {
                    cell?.detailTextLabel?.text = String(video.expired_credentials_count)
                }
            case 2:
                cell?.imageView?.image = UIImage(systemName: "doc.fill.badge.plus")
                cell?.imageView?.tintColor = .systemBlue
                cell?.textLabel?.text = "Extra Credentials"
                if let video = self.video {
                    cell?.detailTextLabel?.text = String(video.extra_credentials_count)
                }
            default:
                break
        }
        
        return cell!
    }
}
