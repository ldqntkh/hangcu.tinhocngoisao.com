import {combineReducers} from 'redux';

// import reducer
import {ProductTypeReducer} from './productTypeReducer';
import {ActionReducer} from './actionReducer';
import {ProductDataReducer} from './productDataReducer';
import {ComputerBuildingDataReducer} from './computerBuildingDataReducer';

let BuildPcReducer = combineReducers({
    ProductTypeReducer,
    ActionReducer,
    ProductDataReducer,
    ComputerBuildingDataReducer
});

export default BuildPcReducer;