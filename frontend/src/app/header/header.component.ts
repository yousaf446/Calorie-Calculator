import { Component, OnInit } from '@angular/core';

import { UserService } from '../services/user.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  constructor(public auth: UserService) { }

  ngOnInit(): void {
  }

  get tokenCheck() {
    return this.auth.token;
  }

  get userRole() {
    return this.auth.role;
  }

  logout() {
    this.auth.logout();
  }

}
