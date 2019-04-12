import * as http from './httpService';

export const getUser = () => {
  return http.getPrivate('/user');
};

export const login = data => {
  return http.postPublic('/login', data);
};

export const register = data => {
  return http.postPublic('/register', data);
};

export const changePassword = (oldPassword, newPassword) => {
  return http.putPrivate('/user', { password: oldPassword, new: newPassword });
};

export const changeName = name => {
  return http.postPrivate('/user', { name });
};
