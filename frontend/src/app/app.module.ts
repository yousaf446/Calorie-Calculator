import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { RouterModule, Routes } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { FoodentryComponent } from './foodentry/list/listfoodentry.component';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HeaderComponent } from './header/header.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { TokenInterceptor } from './services/token.interceptor';
import { NotAuthorizedComponent } from './shared/not-authorized/not-authorized.component';
import { StorefoodentryComponent } from './foodentry/store/storefoodentry.component';
import { ReportsComponent } from './reports/reports.component';
import { AlwaysAuthGuard } from './auth.guard';

@NgModule({
  declarations: [
    AppComponent,
    FoodentryComponent,
    HeaderComponent,
    SidebarComponent,
    NotAuthorizedComponent,
    StorefoodentryComponent,
    ReportsComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: TokenInterceptor,
      multi: true,
    },
    AlwaysAuthGuard
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
