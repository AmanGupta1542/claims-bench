import { Injectable } from '@angular/core';

@Injectable()
export class User {
    id: number;
    name: string;
    username: string;
    email: string;
    appRoleId: number;

//     constructor(data: any) {
//         data = data || {};
//         this.id = data.id;
//         this.name = data.name;
//         this.email = data.email;
//         this.appRoleId = data.appRoleId;
//         this.username = data.username;
//     }

//     public get isAuthenticated(): boolean {
//         return !!this.id;
//     }

//     public get isAdmin() {
//         return this.appRoleId === 1;
//     }

//     public get isVendor() {
//       return this.appRoleId === 3;
//   }

//   public get isUser() {
//     return this.appRoleId === 2;
// }

}
