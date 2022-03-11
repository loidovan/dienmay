const routes = [
    {
        path: "/admin",
        component: require("../components/admin/Index.vue").default,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "",
                name: "admin.dashboard",
                component: require("../components/admin/Dashboard.vue").default
            },
            {
                path: "categories",
                name: "categories",
                component: require("../components/admin/category/List.vue")
                    .default,
                meta: {
                    title: "Danh Mục Sản Phẩm",
                },
            },
            {
                path: "categories/create",
                name: "categories.create",
                component: require("../components/admin/category/Create.vue")
                    .default,
                meta: {
                    title: "Thêm Mới Danh Mục Sản Phẩm",
                },
            },
            {
                path: "categories/edit/:id",
                name: "categories.edit",
                component: require("../components/admin/category/Edit.vue")
                    .default,
                meta: {
                    title: "Chỉnh Sửa Danh Mục Sản Phẩm",
                },
            },
        ],
    },
    {
        path: "/login",
        name: "admin.login",
        component: require("../components/admin/login/Login.vue").default,
        meta: {
            title: "Đăng Nhập",
            requiresAuth: false,
        },
    },
];

export default routes;
