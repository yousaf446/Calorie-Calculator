import { Component, OnInit } from '@angular/core';
import { UserService } from '../services/user.service';
import { appConfig } from '../app.config';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  users = appConfig.usersData;

  constructor(private userService: UserService) { }

  ngOnInit(): void {
  }

  login(userData: any) {
    this.userService.login({email: userData.email, password: userData.password});
  }

  activateUser(id: any) {
    for(let user in this.users) {
      if (user == id) {
        this.users[user]['active'] = true;
      } else {
        this.users[user]['active'] = false;
      }
    }
  }

}
