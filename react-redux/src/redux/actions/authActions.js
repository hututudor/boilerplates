import { UPDATE_USER, LOGOUT } from '../types';

export const updateUser = (user, token) => {
	return {
		type: UPDATE_USER,
		user,
		token
	};
};

export const logout = () => {
	return {
		type: LOGOUT
	};
};
