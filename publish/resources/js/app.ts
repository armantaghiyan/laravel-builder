import { createApp } from 'vue';
import App from './App.vue';
import './../css/app.css';
import router from "@/router.ts";
import { createPinia } from 'pinia'
import i18n from './plugins/i18n';


const pinia = createPinia();
const app = createApp(App);

app.use(pinia);
app.use(router);
app.use(i18n);

// layout components
import AppContent from "@/components/layout/app-content.vue";
import AppFooter from "@/components/layout/app-footer.vue";
import AppHeader from "@/components/layout/app-header.vue";
import AppSidebar from "@/components/layout/app-sidebar.vue";

app.component('app-content', AppContent);
app.component('app-footer', AppFooter);
app.component('app-header', AppHeader);
app.component('app-sidebar', AppSidebar);
// transition components
import FadeTransition from "@/components/custom/animate/fade-transition.vue";
import FadeAnimate from "@/components/custom/animate/fade-animate.vue";

app.component('fade-transition', FadeTransition);
app.component('fade-animate', FadeAnimate);

// app components
import AppLoading from "@/components/custom/app/app-loading.vue";
import I18nProvider from "@/components/custom/app/i18n-provider.vue";
import Logo from "@/components/custom/app/logo.vue";
import Pagination from "@/components/custom/app/pagination.vue";
app.component('app-loading', AppLoading);
app.component('i18n-provider', I18nProvider);
app.component('i18n-provider', I18nProvider);
app.component('logo', Logo);
app.component('pagination', Pagination);

// adapter components
import PermissionAdapter from "@/components/adapter/permission-adapter.vue";
import RoleAdapter from "@/components/adapter/role-adapter.vue";

app.component('permission-adapter', PermissionAdapter);
app.component('role-adapter', RoleAdapter);

// btn components
import BtnPrimary from "@/components/custom/buttons/btn-primary.vue";
import IconButton from "@/components/custom/buttons/icon-button.vue";
import BtnClickable from "@/components/custom/buttons/btn-clickable.vue";
import BtnDelete from "@/components/custom/buttons/btn-delete.vue";
import BtnEdit from "@/components/custom/buttons/btn-edit.vue";
import BtnOption from "@/components/custom/buttons/btn-option.vue";
import BtnPagination from "@/components/custom/buttons/btn-pagination.vue";
import BtnPrimaryAdd from "@/components/custom/buttons/btn-primary-add.vue";
import BtnSearch from "@/components/custom/buttons/btn-search.vue";
import BtnSee from "@/components/custom/buttons/btn-see.vue";

app.component('btn-primary', BtnPrimary);
app.component('icon-button', IconButton);
app.component('btn-clickable', BtnClickable);
app.component('btn-delete', BtnDelete);
app.component('btn-edit', BtnEdit);
app.component('btn-option', BtnOption);
app.component('btn-pagination', BtnPagination);
app.component('btn-primary-add', BtnPrimaryAdd);
app.component('btn-search', BtnSearch);
app.component('btn-see', BtnSee);

// input components
import TextInput from "@/components/custom/inputs/text-input.vue";
import SwitchInput from "@/components/custom/inputs/switch-input.vue";
import PageRows from "@/components/custom/inputs/page-rows.vue";
import UploadInput from "@/components/custom/inputs/upload-input.vue";
import SelectInput from "@/components/custom/inputs/select-input.vue";

app.component('text-input', TextInput);
app.component('switch-input', SwitchInput);
app.component('page-rows', PageRows);
app.component('upload-input', UploadInput);
app.component('select-input', SelectInput);

// menu components
import MenuItem from "@/components/custom/menu/menu-item.vue";
import MenuSection from "@/components/custom/menu/menu-section.vue";
import OptionMenu from "@/components/custom/menu/option-menu.vue";

app.component('menu-item', MenuItem);
app.component('menu-section', MenuSection);
app.component('option-menu', OptionMenu);

// elements components
import ActionContent from "@/components/custom/elements/action-content.vue";
import Badge from "@/components/custom/elements/badge.vue";
import Card from "@/components/custom/elements/card.vue";
import FilterContent from "@/components/custom/elements/filter-content.vue";
import IdFormater from "@/components/custom/elements/id-formater.vue";
import LabelItem from "@/components/custom/elements/label-item.vue";
import LabelTitle from "@/components/custom/elements/label-title.vue";
import Tooltip from "@/components/custom/elements/tooltip.vue";
import UserData from "@/components/custom/elements/user-data.vue";

app.component('action-content', ActionContent)
app.component('badge', Badge)
app.component('card', Card)
app.component('filter-content', FilterContent)
app.component('id-formater', IdFormater)
app.component('label-item', LabelItem)
app.component('label-title', LabelTitle)
app.component('tooltip', Tooltip)
app.component('user-data', UserData)

// table components
import CustomTable from "@/components/custom/table/custom-table.vue";
import CustomTbody from "@/components/custom/table/custom-tbody.vue";
import CustomThead from "@/components/custom/table/custom-thead.vue";
import CustomTh from "@/components/custom/table/custom-th.vue";
import CustomTr from "@/components/custom/table/custom-tr.vue";
import CustomTd from "@/components/custom/table/custom-td.vue";

app.component('custom-table', CustomTable);
app.component('custom-tbody', CustomTbody);
app.component('custom-thead', CustomThead);
app.component('custom-th', CustomTh);
app.component('custom-tr', CustomTr);
app.component('custom-td', CustomTd);

app.mount('#app');
