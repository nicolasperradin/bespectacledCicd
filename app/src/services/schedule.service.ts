// import axios from 'axios'

// const API_URL = import.meta.env.VITE_API_URL

const data = [
	{ id: 1, event: 1, date: '2023-02-10', times: ['20:00'] },
	{ id: 2, event: 1, date: '2023-02-11', times: ['14:00', '20:00'] },
	{ id: 3, event: 1, date: '2023-02-12', times: ['13:00', '18:30'] },
	{ id: 4, event: 1, date: '2023-02-14', times: ['19:00'] },
	{ id: 5, event: 1, date: '2023-02-15', times: ['19:00'] },

	{ id: 6, event: 2, date: '2023-02-10', times: ['20:00'] },
	{ id: 7, event: 2, date: '2023-02-11', times: ['14:00', '20:00'] },
	{ id: 8, event: 2, date: '2023-02-12', times: ['13:00', '18:30'] },
	{ id: 9, event: 2, date: '2023-02-14', times: ['19:00'] },
	{ id: 10, event: 2, date: '2023-02-15', times: ['19:00'] },

	{ id: 11, event: 3, date: '2023-02-10', times: ['20:00'] },
	{ id: 12, event: 3, date: '2023-02-11', times: ['14:00', '20:00'] },
	{ id: 13, event: 3, date: '2023-02-13', times: ['20:00'] },
	{ id: 14, event: 3, date: '2023-02-14', times: ['19:00'] },
	{ id: 15, event: 3, date: '2023-02-15', times: ['14:00', '20:00'] },

	{ id: 16, event: 4, date: '2023-02-10', times: ['20:00'] },
	{ id: 17, event: 4, date: '2023-02-11', times: ['14:00', '20:00'] },
	{ id: 18, event: 4, date: '2023-02-12', times: ['19:00'] },
	{ id: 19, event: 4, date: '2023-02-14', times: ['19:00'] },
	{ id: 20, event: 4, date: '2023-02-15', times: ['19:00'] },

	{ id: 21, event: 5, date: '2023-02-10', times: ['19:00'] },
	{ id: 22, event: 5, date: '2023-02-11', times: ['14:00', '20:00'] },
	{ id: 23, event: 5, date: '2023-02-12', times: ['15:00'] },
	{ id: 24, event: 5, date: '2023-02-14', times: ['19:00'] },
	{ id: 25, event: 5, date: '2023-02-15', times: ['19:00'] },

	{ id: 26, event: 6, date: '2023-02-14', times: ['20:00'] },
	{ id: 27, event: 6, date: '2023-03-26', times: ['20:00'] },
	{ id: 28, event: 6, date: '2023-04-22', times: ['20:00'] },
	{ id: 29, event: 6, date: '2023-05-05', times: ['20:00'] },
	{ id: 30, event: 6, date: '2023-06-02', times: ['20:00'] },

	{ id: 31, event: 7, date: '2023-05-26', times: ['18:30'] },
	{ id: 32, event: 7, date: '2023-05-27', times: ['18:30'] },
	{ id: 33, event: 7, date: '2023-05-28', times: ['18:30'] },

	{ id: 34, event: 8, date: '2023-08-23', times: ['20:30'] },
	{ id: 35, event: 8, date: '2023-08-24', times: ['20:30'] },
	{ id: 36, event: 8, date: '2023-08-26', times: ['20:30'] },
	{ id: 37, event: 8, date: '2023-08-27', times: ['20:30'] }
]

class ScheduleService {
	all() {
		return Promise.resolve({ data })
		// return axios.get(API_URL + 'schedules')
	}
}

export default new ScheduleService()