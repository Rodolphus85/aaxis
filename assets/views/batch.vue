<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Products Batch Update
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="updateList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sku</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in productList" :key="product.sku">
                                <td>{{ product.sku }}</td>
                                <td>
                                    <input type="text" required class="form-control" name="product-name" id="" v-model="product.productName" 
                                        aria-describedby="helpId" placeholder="Add a Product Name"
                                    >
                                </td>
                                <td>                        
                                    <input type="text" required class="form-control" name="description" id="" v-model="product.description" 
                                        aria-describedby="helpId" placeholder="Add a Description"
                                    >
                                </td>
                                <td>
                                    <router-link :to="{name:'edit', params:{sku:product.sku}}" title="Edit" class="btn btn-primary">
                                        <i class="fa-solid fa-edit"></i>
                                    </router-link> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
    data() {
        return {
            productList:[],
        }
    },
    created:function(){
        this.getProductList();
    },
    methods:{
        getProductList(){
            fetch('http://127.0.0.1:8000/api/v1/products/list', {
                method:"GET",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                },                
            })
            .then(response=>response.json())
            .then((dataResponse)=>{
                this.productList = dataResponse;
            })
            .catch(console.log())
        },
        updateList(){
            let productList = JSON.parse(JSON.stringify(this.productList));
            
            let dataSend = {
                productList
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
