import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_URL

const data = [
	{ id: 1, venue: 1, title: 'The Lion King', type: 'broadway', price: 162.84, src: 'https://www.nyc.com/images/r/75803/poster.jpg' },
	{ id: 2, venue: 2, title: 'Aladdin', type: 'broadway', price: 141.45, src: 'https://www.nyc.com/images/r/1206808/poster.jpg' },
	{ id: 3, venue: 3, title: 'The Phantom Of The Opera', type: 'broadway', price: 247.02, src: 'https://www.nyc.com/images/r/1058289/s1058289.jpg' },
	{ id: 4, venue: 4, title: 'Wicked', type: 'broadway', price: 144.9, src: 'https://www.nyc.com/images/r/75773/s75773.jpg' },
	{ id: 5, venue: 5, title: 'Hamilton', type: 'broadway', price: 211.25, src: 'https://www.nyc.com/images/r/1265184/poster.jpg' },
	{ id: 6, venue: 6, title: 'Billy Joel', type: 'concert', price: 58, src: 'https://www.nyc.com/images/concerts/1_billy_joel.jpg' },
	{ id: 7, venue: 7, title: 'Taylor Swift', type: 'concert', price: 892, src: 'https://www.nyc.com/images/concerts/taylor_swift_22.jpg' },
	{ id: 8, venue: 6, title: 'Madonna', type: 'concert', price: 165, src: 'https://www.nyc.com/images/concerts/modonna_23.jpg' },
	{ id: 9, venue: 8, title: 'Wizkid', type: 'concert', price: 62, src: 'https://www.nyc.com/images/concerts/wizkid_23.jpg' }
]

class EventService {
	all() {
		return Promise.resolve({ data })
		// return axios.get(baseUrl + '/events')
	}
}

export default new EventService()