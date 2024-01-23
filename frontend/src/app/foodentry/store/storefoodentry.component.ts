import { formatDate } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { FormBuilder } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Router } from '@angular/router';

import { FoodEntry } from '../../models/FoodEntry';

import { UserService } from '../../services/user.service';
import { FoodentryService } from '../../services/foodentry.service';

declare var jQuery:any;

@Component({
  selector: 'app-storefoodentry',
  templateUrl: './storefoodentry.component.html',
  styleUrls: ['./storefoodentry.component.css']
})
export class StorefoodentryComponent implements OnInit {
  title = 'Add Food Entry';
  foodEntry: FoodEntry = new FoodEntry();
  pipe = new DatePipe('en-US');
  foodEntryForm: any;
  updateId: any;
  responseText: any;
  userConstraintsAvailable: any = false;
  lastEntryRecord: FoodEntry = new FoodEntry();
  lastPrice: number = 0;
  lastCalorieValue: number = 0;

  constructor(private auth: UserService, 
    private foodEntryFormBuilder: FormBuilder,
    private foodentryService: FoodentryService,
    private route: ActivatedRoute,
    public router: Router) {
      this.foodEntryForm = this.foodEntryFormBuilder.group(new FoodEntry());

     }

  ngOnInit(): void {
    let vm = this;
    jQuery('#consumedAt').datetimepicker({
      format:'Y-m-d H:i:s',
      maxDate: new Date(),
      onChangeDateTime:function(dp: any, $input: any){
        console.log(dp);
        vm.foodEntry.consumed_at = $input.val();
        vm.foodEntryForm.controls['consumed_at'].setValue($input.val());
        if (vm.pipe.transform(vm.lastEntryRecord.consumed_at, 'yyyy-MM-dd') == vm.pipe.transform($input.val(), 'yyyy-MM-dd')) {
          vm.lastCalorieValue = vm.lastEntryRecord.calorie_value;
        } else {
          vm.lastCalorieValue = 0;
        }
        if (vm.pipe.transform(vm.lastEntryRecord.consumed_at, 'MM') == vm.pipe.transform($input.val(), 'MM')) {
          vm.lastPrice = vm.lastEntryRecord.price;
        } else {
          vm.lastPrice = 0;
        }
        vm.userConstraints(vm.foodEntry.user_id, vm.foodEntry.consumed_at);
      }
    });
    this.updateId = this.route.snapshot.paramMap.get('id') || null;
    if (this.updateId != null) {
      this.title = 'Update Food Entry';
      this.foodentryService.getFoodEntryById(this.updateId).subscribe(
        (data: any) => {
          this.foodEntry = new FoodEntry(data);
          this.foodEntryForm = this.foodEntryFormBuilder.group(this.foodEntry);
          this.lastEntryRecord = new FoodEntry(data);
          this.lastPrice = this.lastEntryRecord.price;
          this.lastCalorieValue = this.lastEntryRecord.calorie_value;
          this.userConstraints(this.foodEntry.user_id, this.foodEntry.consumed_at);
          
          
        },
        error => {
          console.log('error', error);
          
        });    
    } else {
      this.foodEntry = new FoodEntry({
        product_name: "",
        calorie_value: 0,
        price: 0,
        consumed_at: this.pipe.transform(Date.now(), 'yyyy-MM-dd hh:mm:ss'),
        user_id: this.auth.user 
      });
      this.foodEntryForm = this.foodEntryFormBuilder.group(this.foodEntry);
      this.userConstraints(this.foodEntry.user_id, this.foodEntry.consumed_at);
    }
  }

  userConstraints(user_id: string, date: Date) {
    let userData = {
      "user_id": user_id,
      "date": date
    };
    this.foodentryService.userConstraints(userData).subscribe(
      (data: any) => {
        this.foodEntry.userConstraints = data;
        this.foodEntryForm.controls['userConstraints'].setValue(data);
        this.userConstraintsAvailable = true;
      },
      error => {
        console.log('error', error);
        
      });    
  }

  onSubmit() {
    this.responseText = "";
    this.foodEntry = this.foodEntryForm.value;
    if (this.updateId != null) {
      this.foodentryService.updateFoodEntry(this.foodEntry).subscribe(
        (data: any) => {
          this.responseText = data.message;
          this.router.navigate(['./foodentries']);
        },
        error => {
          if (error.error.errors != undefined) {
            this.displayErrors(error.error.errors)
          }
          
        });
    } else {
      this.foodentryService.addFoodEntry(this.foodEntry).subscribe(
        (data: any) => {
          this.responseText = data.message;
          this.router.navigate(['./foodentries'])
        },
        error => {
          if (error.error.errors != undefined) {
            this.displayErrors(error.error.errors)
          }
          
        });
    }    
  }

  displayErrors(errors: any) {
    for (let err in errors) {
      console.log(errors[err]);
      for (let msg in errors[err]) {
        this.responseText += errors[err][msg];
      }
    }
  }

  get caloriesConsumed() {
    return this.foodEntryForm.controls['userConstraints'].value.calories_consumed + this.foodEntryForm.controls['calorie_value'].value - this.lastCalorieValue;
  }

  get budgetSpent() {
    return this.foodEntryForm.controls['userConstraints'].value.budget_spent + this.foodEntryForm.controls['price'].value - this.lastPrice;
  }

  cancel() {
    this.router.navigate(['./foodentries'])
  }

}
