export default function headers() {
	const currentUser = JSON.parse(localStorage.getItem('user'));
	if (currentUser && currentUser.token) return { Authorization: 'Bearer ' + currentUser.token };
	else return {};
}