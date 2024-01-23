import { CanActivate, Router } from '@angular/router';
import { Injectable } from '@angular/core';
import { UserService } from './services/user.service';

@Injectable()
export class AlwaysAuthGuard implements CanActivate {
  constructor(private router: Router, private auth: UserService) { }
  canActivate() {
    if (this.auth.checkTokenExpiry) {
        this.auth.logout();
        return false;
    } else {
      return true;
    }
  }
}
