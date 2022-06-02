import {combineReducers} from 'redux';

// import reducer
import {ActionReducer} from './actionReducer';
import {ProductTypeReducer} from './productTypeReducer';
import {ListProductReducer} from './listProductReducer';

let BuildPcReducer = combineReducers({
    ActionReducer,
    ProductTypeReducer,
    ListProductReducer
});

export default BuildPcReducer;