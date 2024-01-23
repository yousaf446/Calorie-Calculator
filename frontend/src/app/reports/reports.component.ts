import { Component, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';

import { Reports } from '../models/Reports';
import { FoodentryService } from '../services/foodentry.service';

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.css']
})
export class ReportsComponent implements OnInit {
  title="Reports";
  reportsData: Reports = new Reports();
  showReports = false;
  pipe = new DatePipe('en-US');

  constructor(private foodentryService: FoodentryService) { }

  ngOnInit(): void {
    this.getReports();
  }

  getReports() {
    let dateNow = this.pipe.transform(Date.now(), 'yyyy-MM-dd HH:mm:ss');
    this.foodentryService.getReports(dateNow)
    .subscribe(
      (data: Reports) => {
        this.reportsData = data;
        this.showReports = true;
      },
      (error: any) => {
        console.log('error', error);
        
      }); 
  }

}
