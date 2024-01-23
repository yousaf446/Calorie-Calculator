import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Router } from '@angular/router';

import { JwtHelperService } from "@auth0/angular-jwt";
import { User } from '../models/User';
import { appConfig } from '../app.config';

const jwtHelper = new JwtHelperService();

@Injectable({
  providedIn: 'root'
})
export class UserService {
  API_PATH = appConfig.apiUrl + '/api';

  constructor(private http: HttpClient, public router: Router) { }

  login(credentials: { email: string, password: string }) {
    let user: User;
    this.http.post(this.API_PATH + '/login', credentials).subscribe(
      (data: any) => {
        let decodedToken = jwtHelper.decodeToken(data.access_token);
        sessionStorage.setItem('token', data.access_token);
        sessionStorage.setItem('role', decodedToken.role);
        sessionStorage.setItem('user', decodedToken.sub);
        sessionStorage.setItem('expiry', decodedToken.exp);
        this.router.navigateByUrl('/', {skipLocationChange: true}).then(()=>
          this.router.navigate(['./foodentries'])
        );
      },
      error => {
        console.log('error', error);
        
      });    
  }

  logout() {
    sessionStorage.removeItem('token');
    sessionStorage.removeItem('role');
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('expiry');
    this.router.navigate(['/'])
  }

  get token(): string|null {
    return sessionStorage.getItem('token') || null;
  }

  get role(): string|null {
    return sessionStorage.getItem('role') || null;
  }

  get user(): string|null {
    return sessionStorage.getItem('user') || null;
  }

  get tokenExpiry(): string|null {
    return sessionStorage.getItem('expiry') || null;
  }

  get checkTokenExpiry(): boolean {
    let timestamp = new Date().getTime();
    let dateNow: number = timestamp / 1000;
    let check = false;
    let expiryDate: any = this.tokenExpiry;
    if (expiryDate != null) {
      if (dateNow > parseInt(expiryDate)) {
        check =  true;
      }
    }  
    return check;
  }
}
