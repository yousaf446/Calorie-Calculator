import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FoodentryComponent } from './foodentry/list/listfoodentry.component';
import { StorefoodentryComponent } from './foodentry/store/storefoodentry.component';
import { ReportsComponent } from './reports/reports.component';

import { NotAuthorizedComponent } from './shared/not-authorized/not-authorized.component';
import { AlwaysAuthGuard } from './auth.guard';

const routes: Routes = [
  { path: 'foodentries', component: FoodentryComponent, canActivate: [AlwaysAuthGuard] },
  { path: 'add-foodentry', component: StorefoodentryComponent, canActivate: [AlwaysAuthGuard] },
  { path: 'edit-foodentry/:id', component: StorefoodentryComponent, canActivate: [AlwaysAuthGuard] },
  { path: 'not-authorized', component: NotAuthorizedComponent, canActivate: [AlwaysAuthGuard] },
  { path: 'reports', component: ReportsComponent, canActivate: [AlwaysAuthGuard] }
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {
    onSameUrlNavigation: 'reload',
  })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
