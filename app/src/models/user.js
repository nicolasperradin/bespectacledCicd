export default class User {
	constructor(username, email, password, verified_at) {
		this.username = username;
		this.email = email;
		this.password = password;
		this.verified_at = verified_at;
	}
}