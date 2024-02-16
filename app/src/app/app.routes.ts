import { Routes } from '@angular/router';
import { ListComponent } from './pages/list/list.component';
import { CreateComponent } from './pages/create/create.component';

export const routes: Routes = [
    { 'path' : '', component: ListComponent },
    { 'path' : 'create', component: CreateComponent }
];
