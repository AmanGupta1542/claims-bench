import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class HttpService {
  constructor(public http: HttpClient) { }

  setAuthHeaders() {
    const headerJson: any = {};
    const token = localStorage.getItem('access_token') || sessionStorage.getItem('access_token');
    if (token) {
      headerJson['Content-Type'] = 'application/json';
      headerJson['Authorization'] = 'Bearer ' + token;
    }
    return new HttpHeaders(headerJson);
  }

  get(url: string) {
    return this.http.get(url) as Observable<any>;
  }

  post(url: string, model: any) {
    return this.http.post(url, model) as Observable<any>;
  }

  put(url: string, model: any) {
    return this.http.put(url, model) as Observable<any>;
  }

  delete(url: string, model: any = null) {
    return this.http.delete(url) as Observable<any>;
  }

  // handleException(err: any) {
  //   if (err.statusText === 'Unauthorized') {
  //     console.log('Your session has been expired, please re-login.');
  //     // location.reload();
  //   } else {
  //     console.log(err.message || `Something went wrong while calling api.`);
  //     return Observable.throw(err);
  //   }
  // }
}
