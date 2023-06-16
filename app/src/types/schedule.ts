import type { Item } from './item'
import type { Event } from './event'

export interface Schedule extends Item {
	event: Event
	date: string
	times: string[]
}