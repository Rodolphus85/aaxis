<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Products
            </div>
            <div class="card-body">
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
                        <tr v-for="(product) in productList" :key="product.sku">
                            <td>{{ product.sku }}</td>
                            <td>{{ product.productName }}</td>
                            <td>{{ product.description }}</td>
                            <td>
                                <router-link :to="{name:'edit', params:{sku:product.sku}}" title="Edit" class="btn btn-primary">
                                    <i class="fa-solid fa-edit"></i>
                                </router-link> 
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                console.log(dataResponse);
                this.productList = dataResponse;
            })
            .catch(console.log())
        },
    }
}
</script>
