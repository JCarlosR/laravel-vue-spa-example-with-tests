import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Modal from './Modal'
import Checkbox from './Checkbox'
import { HasError, AlertError, AlertSuccess } from 'vform'
import TaskTable from "./TaskTable";
import TaskSection from "./TaskSection";
import UserTable from "./UserTable";
import UserSection from "./UserSection";

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
    
    TaskTable, TaskSection,
    UserTable, UserSection
].forEach(Component => {
    Vue.component(Component.name, Component);
});
