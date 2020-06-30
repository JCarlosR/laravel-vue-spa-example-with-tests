import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Modal from './Modal'
import Checkbox from './Checkbox'
import { HasError, AlertError, AlertSuccess } from 'vform'
import TaskTable from "./TaskTable";
import TaskDate from "./TaskDate";

// Components that are registered globaly.
[
    Card,
    Child,
    Button,
    Modal,
    Checkbox,
    HasError,
    AlertError,
    AlertSuccess,
    
    TaskTable, TaskDate
].forEach(Component => {
    Vue.component(Component.name, Component);
});
