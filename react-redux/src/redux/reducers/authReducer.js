import { UPDATE_USER, LOGOUT } from '../types';
import * as token from '../../services/tokenService';

const initialState = {
  user: {
    auth: false
  }
};

function authReducer(state = initialState, action) {
  let newState = { ...state };

  switch (action.type) {
    case UPDATE_USER:
      newState.user = action.user;
      newState.user.auth = true;
      if (action.token) {
        token.set(action.token);
      }
      break;
    case LOGOUT:
      newState.user = { auth: false };
      token.remove();
      break;
    default:
      break;
  }

  return newState;
}

export default authReducer;
