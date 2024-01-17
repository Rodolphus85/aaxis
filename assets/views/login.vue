<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Login
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="getLogin">
                    <div class="form-group">
                        <input type="text" required class="form-control" name="user-name" id="" v-model="user.username" 
                            aria-describedby="helpId" placeholder="Add a Product Name"
                        >
                        <small id="helpId" class="text-muted">Edit Product Name</small>
                    </div>
                    <div class="form-group">
                        <input type="text" required class="form-control" name="password" id="" v-model="user.password" 
                            aria-describedby="helpId" placeholder="Add a Description"
                        >
                        <small id="helpId" class="text-muted">Edit Description</small>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-success">Submit</button>
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
            user:{},
        }
    },
    methods:{
        getLogin(){
            let dataSend = {
                "username": this.user.username,
                "password": this.user.password
            }
            fetch('http://127.0.0.1:8000/api/login_check', {
                method:"POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body:JSON.stringify(dataSend)
            })
            .then(response=>response.json())
            .then((dataResponse)=>{
                if (dataResponse.token == undefined) {
                    console.log(dataResponse.message)
                    this.$flashMessage.show({
                        type: 'error',
                        title: 'Error', 
                        text: dataResponse.message
                    });
                } else {
                    localStorage.setItem('token', dataResponse.token);
                    window.location.href='/list';
                }
            })
            .catch(console.log())
        }
    }
}
</script>