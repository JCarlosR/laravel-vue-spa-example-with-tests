import Vue from 'vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

import {
    faUser, faLock, faSignOutAlt, faCog, 
    faCalendar, faCalendarDay,
    faInfo, faEdit, faTrash, faFileExport
} from '@fortawesome/free-solid-svg-icons'

/*import {
    faGithub
} from '@fortawesome/free-brands-svg-icons'*/

library.add(
    faUser, faLock, faSignOutAlt, faCog, 
    faCalendar, faCalendarDay,
    faInfo, faEdit, faTrash, faFileExport
);

Vue.component('fa', FontAwesomeIcon);
