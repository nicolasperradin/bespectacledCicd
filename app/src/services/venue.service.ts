// import axios from 'axios'

// const API_URL = import.meta.env.VITE_API_URL

const data = [
	{ id: 1, name: 'Minskoff Theatre', type: 'broadway', seats: 1710, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Minskoff_Theatre_NYC_2007.jpg/375px-Minskoff_Theatre_NYC_2007.jpg' },
	{ id: 2, name: 'New Amsterdam Theatre', type: 'broadway', seats: 1702, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/94/New_Amsterdam_Theatre.jpg/338px-New_Amsterdam_Theatre.jpg' },
	{ id: 3, name: 'Majestic Theatre', type: 'broadway', seats: 1681, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Majestic_Theatre_-_NYC_%2852302522949%29.jpg/375px-Majestic_Theatre_-_NYC_%2852302522949%29.jpg' },
	{ id: 4, name: 'Gershwin Theatre', type: 'broadway', seats: 1933, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Gershwin2022.jpg/390px-Gershwin2022.jpg' },
	{ id: 5, name: 'Richard Rodgers Theatre', type: 'broadway', seats: 1400, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c6/Rodgers_Theater_-_Hamilton_%2848193460677%29.jpg/375px-Rodgers_Theater_-_Hamilton_%2848193460677%29.jpg' },
	{ id: 6, name: 'Madison Square Garden', type: 'concert', seats: 20000, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Madison_Square_Garden_%28MSG%29_-_Full_%2848124330357%29.jpg/413px-Madison_Square_Garden_%28MSG%29_-_Full_%2848124330357%29.jpg' },
	{ id: 7, name: 'MetLife Stadium', type: 'concert', seats: 82500, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Metlife_stadium_%28Aerial_view%29.jpg/375px-Metlife_stadium_%28Aerial_view%29.jpg' },
	{ id: 8, name: 'Barclays Center', type: 'concert', seats: 17000, price: 10000, src: 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/df/BarclayCenter-2_%2848034233762%29.jpg/413px-BarclayCenter-2_%2848034233762%29.jpg' }
]

// add price for reservation

class VenueService {
	all() {
		return Promise.resolve({ data })
		// return axios.get(API_URL + 'venues')
	}
}

export default new VenueService()