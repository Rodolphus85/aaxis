<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Edit Product {{ product.sku }}
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="updateList">
                    <div class="form-group">
                        <input type="text" required class="form-control" name="product-name" id="" v-model="product.productName" 
                            aria-describedby="helpId" placeholder="Add a Product Name"
                        >
                        <small id="helpId" class="text-muted">Edit Product Name</small>
                    </div>
                    <div class="form-group">
                        <input type="text" required class="form-control" name="description" id="" v-model="product.description" 
                            aria-describedby="helpId" placeholder="Add a Description"
                        >
                        <small id="helpId" class="text-muted">Edit Description</small>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-success">Apply</button>
                        <router-link :to="{name:'list'}" class="btn btn-warning">Cancel</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            product:{},
        }
    },
    created:function() {
        this.getProduct();
    },
    methods:{
        getProduct(){
            let dataSend = {
                "productSkuList": [{
                    "sku": this.$route.params.sku,
                }]
            }
            fetch('http://127.0.0.1:8000/api/v1/products/load', {
                method:"POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                },
                
                body:JSON.stringify(dataSend)
            })
            .then(response=>response.json())
            .then((dataResponse)=>{
                this.product = dataResponse[0];
            })
            .catch(console.log())
        },
        updateList(){
            let dataSend = {
                "productList": [{
                    "sku": this.$route.params.sku,
                    "productName": this.product.productName,
                    "description": this.product.description
                }]
            }
            fetch('http://127.0.0.1:8000/api/v1/products/update', {
                method:"POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                },
                body:JSON.stringify(dataSend)
            })
            .then(response=>response.json())
            .then((dataResponse=>{
                if (dataResponse.success) {
                    this.$flashMessage.show({
                    type: 'success',
                    title: 'Update!',
                    text: dataResponse.message
                });
                } else {
                    this.$flashMessage.show({
                        type: 'error',
                        title: 'Error', 
                        text: dataResponse.message
                    });
                }
            }))
        }
    }
}
</script>