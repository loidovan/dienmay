const routes = [
    {
        path: "",
        component: require("../components/user/Index.vue").default,
        meta: {
            breadcrumb: "Điện Máy Như Ý",
        },
        children: [
            {
                path: "",
                name: "user.home",
                component: require("../components/user/Home.vue").default,
            },
            {
                path: "products",
                name: "user.products",
                component: require("../components/user/products/List.vue").default,
            }
        ],
    },
];

export default routes;
