import { Component, OnInit } from '@angular/core';
import { FoodEntry } from '../../models/FoodEntry';
import { FoodentryService } from '../../services/foodentry.service';
import { UserService } from '../../services/user.service';

declare var jQuery:any;

@Component({
  selector: 'app-foodentry',
  templateUrl: './listfoodentry.component.html',
  styleUrls: ['./listfoodentry.component.css']
})
export class FoodentryComponent implements OnInit {
  title = 'Food Entry';
  roleCheck = this.auth.role;
  fromEntryDate: Date = new Date();
  toEntryDate: Date = new Date();
  allFoodEntries: FoodEntry[] = [];
  filteredFoodEntries: FoodEntry[] = [];
  showFoodEntries = false;

  constructor(private foodentryService: FoodentryService, private auth: UserService) { }

  ngOnInit(): void {
    let vm = this;
    this.getFoodEntries();
    jQuery('#fromEntryDate').datetimepicker({
      format:'Y-m-d H:i:s',
      maxDate: new Date(),
      onChangeDateTime:function(dp: any, $input: any){
        vm.fromEntryDate = dp;
        jQuery('#toEntryDate').datetimepicker({
          format:'Y-m-d H:i:s',
          maxDate: new Date(),
          minDate: dp
        });
      }
    });
    jQuery('#toEntryDate').datetimepicker({
      format:'Y-m-d H:i:s',
      maxDate: new Date(),
      onChangeDateTime:function(dp: any, $input: any){
        vm.toEntryDate = dp;
        jQuery('#fromEntryDate').datetimepicker({
          format:'Y-m-d H:i:s',
          maxDate: dp,
        });
      }
    });
  }

  getFoodEntries(): void {
    this.foodentryService.getFoodEntries()
    .subscribe(
      (data: FoodEntry[]) => {
        this.filteredFoodEntries = data;
        this.allFoodEntries = data;
        this.showFoodEntries = true;
      },
      error => {
        console.log('error', error);
        
      });    
  }

  delete(id: any) {
    if(confirm("Are you sure to delete this food entry?")) {
      this.foodentryService.deleteFoodEntry(id)
      .subscribe(foodEntries => this.getFoodEntries());
    }
  }

  filterByDate() {
    this.filteredFoodEntries = this.allFoodEntries.filter(a => {
      var date = new Date(a.consumed_at);
      return (date >= this.fromEntryDate && date <= this.toEntryDate);
    });
  }

}
