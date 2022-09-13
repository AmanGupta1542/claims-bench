import { Component, OnInit } from '@angular/core';
import {MenuItem} from 'primeng/api';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.css']
})
export class NavComponent implements OnInit {
  items: MenuItem[];
  constructor() { }

  ngOnInit() {
    this.items = [
        {
            label:'Home',
            icon:'pi pi-fw pi-home',
            routerLink: '/'
        },
        {
            label:'User',
            icon:'pi pi-fw pi-file',
            items:[
                {
                    label:'Settings',
                    icon:'pi pi-fw pi-cog',
                    items:[
                    {
                        label:'Profile',
                        icon:'pi pi-fw pi-user',
                        routerLink: ['/user/profile'], 
                    },
                    {
                        label:'Change Password',
                        icon:'pi pi-fw pi-pencil',
                        routerLink: ['/user/change-password'], 
                    },

                    ]
                },
                // {
                //     label:'Delete',
                //     icon:'pi pi-fw pi-trash'
                // },
                {
                    separator:true
                },
                {
                    label:'Logout',
                    icon:'pi pi-power-off'
                }
            ]
        },
        // {
        //     label:'Edit',
        //     icon:'pi pi-fw pi-pencil',
        //     items:[
        //         {
        //             label:'Left',
        //             icon:'pi pi-fw pi-align-left'
        //         },
        //         {
        //             label:'Right',
        //             icon:'pi pi-fw pi-align-right'
        //         },
        //         {
        //             label:'Center',
        //             icon:'pi pi-fw pi-align-center'
        //         },
        //         {
        //             label:'Justify',
        //             icon:'pi pi-fw pi-align-justify'
        //         },

        //     ]
        // },
        // {
        //     label:'Users',
        //     icon:'pi pi-fw pi-user',
        //     items:[
        //         {
        //             label:'New',
        //             icon:'pi pi-fw pi-user-plus',

        //         },
        //         {
        //             label:'Delete',
        //             icon:'pi pi-fw pi-user-minus',

        //         },
        //         {
        //             label:'Search',
        //             icon:'pi pi-fw pi-users',
        //             items:[
        //             {
        //                 label:'Filter',
        //                 icon:'pi pi-fw pi-filter',
        //                 items:[
        //                     {
        //                         label:'Print',
        //                         icon:'pi pi-fw pi-print'
        //                     }
        //                 ]
        //             },
        //             {
        //                 icon:'pi pi-fw pi-bars',
        //                 label:'List'
        //             }
        //             ]
        //         }
        //     ]
        // },
        // {
        //     label:'Events',
        //     icon:'pi pi-fw pi-calendar',
        //     items:[
        //         {
        //             label:'Edit',
        //             icon:'pi pi-fw pi-pencil',
        //             items:[
        //             {
        //                 label:'Save',
        //                 icon:'pi pi-fw pi-calendar-plus'
        //             },
        //             {
        //                 label:'Delete',
        //                 icon:'pi pi-fw pi-calendar-minus'
        //             },

        //             ]
        //         },
        //         {
        //             label:'Archieve',
        //             icon:'pi pi-fw pi-calendar-times',
        //             items:[
        //             {
        //                 label:'Remove',
        //                 icon:'pi pi-fw pi-calendar-minus'
        //             }
        //             ]
        //         }
        //     ]
        // },
        {
            label:'Login',
            icon:'pi pi-fw pi-power-off',
            routerLink: '/auth/login'
        },
        {
            label:'Register',
            icon:'pi pi-fw pi-power-off',
            routerLink: ['/auth/register'],        
        },
        {
          label:'Dashboard',
          icon:'pi pi-fw pi-home',
          routerLink: ['/user/dashboard'],          
      }
    ];
  }    

}
