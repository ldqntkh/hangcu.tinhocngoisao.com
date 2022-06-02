import { createStore } from 'redux';

// import reducer
import BuildPcReducer from '../reducers/indexReducer';

export const BuildPcStoreBM = createStore(BuildPcReducer);