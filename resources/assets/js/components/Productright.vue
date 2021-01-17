<template>
    <div class="ps-shopping ps-tab-root">
        <div class="ps-shopping__header">
            <p><strong v-if="products.length != 0"> {{products.data.data.length}} </strong> Products found</p>
            <div class="ps-shopping__actions">
                <!-- <select class="ps-select" data-placeholder="Sort Items">
                    <option>Sort by latest</option>
                    <option>Sort by popularity</option>
                    <option>Sort by average rating</option>
                    <option>Sort by price: low to high</option>
                    <option>Sort by price: high to low</option>
                </select> -->
                <div class="ps-shopping__view">
                    <p>View</p>
                    <ul class="ps-tab-list">
                        <li class="active"><a href="#tab-1"><i class="icon-grid"></i></a></li>
                        <li><a href="#tab-2"><i class="icon-list4"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ps-tabs">
            <div class="ps-tab active" id="tab-1">
                <product-lists v-if="products.length != 0" :products="products.data.data"></product-lists>
                
                <div class="ps-pagination">
                    <div class='row' style="display: block;">
                        <pagination v-if="products.length != 0" :data="products.data" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
            <div class="ps-tab" id="tab-2">
                <product-thumbnail-lists v-if="products.length != 0" :products="products.data.data"></product-thumbnail-lists>
                <div class="ps-pagination">
                    <div class='row' style="display: block;">
                        <pagination v-if="products.length != 0" :data="products.data" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default{
        data(){
            return {
            }
        },

        computed: {
            products(){
                return this.$store.getters.PRODUCTS_BY_CATEGORY;
            }
        },
        
        methods: {
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
      
                const body ={
                    word: this.$store.getters.WORD,
                    by: this.$store.getters.BY,
                    min_price: this.$store.getters.MIN_PRICE,
                    max_price: this.$store.getters.MAX_PRICE,
                    category: this.$store.getters.CATEGORY,
                    page: page
                }

                this.$store.dispatch('GET_PRODUCTS', body);
            }
        },

        created: async function() {
            var queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const category = urlParams.get('category');
            const word = urlParams.get('word');

            if(category == '') {
                var value = -1;
            }else{
                var value = category;
            }

            if(word == '') {
                var w_val = '';
            }else{
                var w_val = word;
            }

            const body ={
                word: w_val,
                by: '',
                min_price: 0,
                max_price: 1000000,
                category: value,
                page: 1
            }

            const res1 = await this.$store.dispatch('GET_PRODUCTS', body);
        }
    }
</script>