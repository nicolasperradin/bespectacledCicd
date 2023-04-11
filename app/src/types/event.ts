import type { Item } from './item'

export interface Event extends Item {
	title: string
	type: string
	price: number
	src?: string
	venue?: any /* TODO Venue */
	artists: any /* TODO User[] */
	schedules: Schedule[]
}

export interface Schedule extends Item {
	event: Event
	date: string
	times: string[]
}