const names = {
	list: 'VenueList',
	create: 'VenueCreate',
	update: 'VenueUpdate',
	show: 'VenueShow'
}

const breadcrumbs = {
	list: { title: names.list, to: { name: names.list } },
	create: { title: names.create, to: { name: names.create } },
	update: { title: names.update, to: { name: names.update } },
	show: { title: names.show, to: { name: names.show } }
}

export default [
	{
		name: names.list,
		path: 'venues',
		component: () => import('@/views/venue/ViewList.vue'),
		meta: { breadcrumb: [breadcrumbs.list] }
	},
	{
		name: names.create,
		path: 'venues/create',
		component: () => import('@/views/venue/ViewCreate.vue'),
		meta: { breadcrumb: [breadcrumbs.list, breadcrumbs.create] }
	},
	{
		name: names.update,
		path: 'venues/edit/:id',
		component: () => import('@/views/venue/ViewUpdate.vue'),
		meta: { breadcrumb: [breadcrumbs.list, breadcrumbs.update] }
	},
	{
		name: names.show,
		path: 'venues/show/:id',
		component: () => import('@/views/venue/ViewShow.vue'),
		meta: { breadcrumb: [breadcrumbs.list, breadcrumbs.show] }
	}
]