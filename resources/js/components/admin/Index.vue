<template>
    <div>
        <div v-if="$route.name != 'admin.login'">
            <HeaderAdmin/>
        </div>
        <router-view></router-view>
        <div v-if="$route.name != 'admin.login'">
            <FooterAdmin/>
        </div>
    </div>
</template>
<script>
import HeaderAdmin from './Header.vue';
import FooterAdmin from './Footer.vue';
export default {
    components: {
        HeaderAdmin,
        FooterAdmin
    }
};
</script>
<style></style>
