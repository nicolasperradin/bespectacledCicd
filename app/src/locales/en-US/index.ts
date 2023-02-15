import room from './room'
import user from './user'
import event from './event'
import ticketing from './ticketing'
import roomreservation from './roomreservation'
import userconfirmation from './userconfirmation'
import paymenttransaction from './paymenttransaction'

export default {
	home: 'Home',
	submit: 'Submit',
	reset: 'Reset',
	add: 'Add',
	delete: 'Delete',
	edit: 'Edit',
	show: 'Show',
	cancel: 'Cancel',
	updated: 'Updated',
	filters: 'Filters',
	filter: 'Filter',
	actions: 'Actions',
	id: 'Id',
	itemCreated: '{0} created',
	itemUpdated: '{0} updated',
	itemDeleted: '{0} deleted',
	itemDeletedByAnotherUser: '{0} deleted by another user',
	field: 'Field',
	value: 'Value',
	itemNotFound: 'No item found. Please reload',
	confirmDelete: 'Are you sure you want to delete this item?',
	loading: 'Loading...',
	room,
	user,
	event,
	ticketing,
	roomreservation,
	userconfirmation,
	paymenttransaction
}
