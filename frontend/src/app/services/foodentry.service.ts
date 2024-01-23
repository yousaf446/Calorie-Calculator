import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

import { FoodEntry } from '../models/FoodEntry';
import { appConfig } from '../app.config';

@Injectable({
  providedIn: 'root'
})
export class FoodentryService {
  API_PATH = appConfig.apiUrl + '/api/foodentry';

  constructor(private http: HttpClient) { }

  getFoodEntries(): Observable<FoodEntry[]> {
    return this.http.get<FoodEntry[]>(this.API_PATH + '/all');
  }

  getFoodEntryById(id: string): Observable<FoodEntry> {
    return this.http.get<FoodEntry>(this.API_PATH + '/' + id);
  }

  addFoodEntry(foodEntry: FoodEntry): Observable<any> {
    return this.http.post<any>(this.API_PATH + '/store', foodEntry);
  }

  updateFoodEntry(foodEntry: FoodEntry): Observable<any> {
    return this.http.put<any>(this.API_PATH + '/update', foodEntry);
  }

  deleteFoodEntry(id: string): Observable<any> {
    return this.http.delete<any>(this.API_PATH + '/delete/' + id);
  }

  userConstraints(userData: any): Observable<any> {
    return this.http.post<any>(this.API_PATH + '/user-constraints', userData);
  }

  getReports(date: any): Observable<any> {
    return this.http.get<any>(this.API_PATH + '/reports/' + date);
  }
}
