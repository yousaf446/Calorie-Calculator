import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpResponse,
  HttpErrorResponse
} from '@angular/common/http';

import { Observable } from 'rxjs';
import { of } from 'rxjs';
import { tap, catchError } from 'rxjs/operators';
import { UserService } from './user.service';
import { Router } from '@angular/router';

@Injectable()
export class TokenInterceptor implements HttpInterceptor {

  constructor(
    public auth: UserService,
    public router: Router,
  ) { }

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // console.log('in token inter ceptor : ', request.url);
    let token = this.auth.token;
    if (token == null) {
      this.router.navigate(['not-authorized']);
    }
    if (!request.url.includes('login')) {
      // add header token information
      request = request.clone({
        setHeaders: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.auth.token}`
        }
      });
    }

    // return next.handle(request);

    return next.handle(request).pipe(
      tap(
        event => this.handleResponse(request, event),
        error => this.handleError(request, error)
      )
    );
  }

  handleResponse(req: HttpRequest<any>, event: HttpEvent<any>) {
    console.log('Handling response for ', req.url, event);
    if (event instanceof HttpResponse) {
      console.log('Request for ', req.url,
          ' Response Status ', event.status,
          ' With body ', event.body);
    }
  }

  handleError(req: HttpRequest<any>, event: any) {
    
    console.error('Request for ', req.url,
          ' Response Status ', event.status,
          ' With error ', event.error);
          if (event.status === 401) {
            this.router.navigate(['not-authorized']);
          }
  }
}
