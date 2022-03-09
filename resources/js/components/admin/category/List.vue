<template>
  <div>
        <h2 class="text-center">Categories List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <!--<th>Actions</th>-->
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <td>{{category.id}}</td>
                    <td>{{category.name}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <router-link :to="{name: 'category.edit', params: {id: category.id}}" class="btn btn-success">Edit</router-link>
                            <button class="btn btn-danger" @click="deleteCategory(category.id)">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: []
        }
    },
    created() {
        this.getCategories();
    },
    methods: {
        getCategories() {
            axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deleteCategory(id) {
            axios.delete('/api/categories/' + id)
                .then(response => {
                    this.getCategorys();
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

<style>

</style>