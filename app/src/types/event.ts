import type { Item } from './item'

export interface Event extends Item {
	title?: string
	type?: string
	price?: number
	src?: number
	venue?: any
	artists?: any
	schedules?: string
}