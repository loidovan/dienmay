const routes = [
    {
        path: '/admin',
        name: 'admin',
        component: require('../components/admin/Index.vue').default,
        children: [
            {
                path: '',
                name: 'admin.dashboard',
                component: require('../components/admin/Dashboard.vue').default,
            },
        ]
    },
    {
        path: '/admin/categories',
        name: 'categories',
        component: require('../components/admin/category/List.vue').default
    },
    {
        path: '/admin/categories/create',
        name: 'categories.create',
        component: require('../components/admin/category/Create.vue').default
    },
    {
        path: '/admin/categories/edit/:id',
        name: 'categories.edit',
        component: require('../components/admin/category/Edit.vue').default
    },
];

export default routes;
