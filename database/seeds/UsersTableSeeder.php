<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'bmpinfology1',
                'email' => 'bmp@bmpinfology.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$TJMihkyrqc7gGURfyh0CR.22gHy3/mYvN9fMeDb5E4jVOBtQU1BK2',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => 'mGquAID8fitEqzo8bkiI5Uh61s5J9VvdAn2XRRvHea0uxzDVxyOxqBfjRjg9',
                'created_at' => NULL,
                'updated_at' => '2019-10-21 12:04:20',
                'department_id' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'bikramkc',
                'email' => 'bikram@bmpinfology.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$xMCdcvRC6pBdSW7txg13Fex.qrSELwWoq/.fQUedt/rz1IC77TIhi',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => 'JbAebaTcBUUGvZh9nnlLivpRaTUP9A0lXCl0k8qC7V9EaND7999KTDIk7a7e',
                'created_at' => NULL,
                'updated_at' => NULL,
                'department_id' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'pradeeprimal',
                'email' => 'pk.rimal@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$YlBKLNSTS2jregGwDMVyROc7GejoJuIcuBaebEmBV5pzxttFbmojW',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'department_id' => 0,
            ),
            3 => 
            array (
                'id' => 8,
                'name' => 'bbsmheadoffice',
                'email' => 'bbsmheadoffice@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$bmAympaZJgUcuLvOdWKchuJhCzORT9sxANwal5j5cJ4IIHKuW8j7K',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'department_id' => 0,
            ),
            4 => 
            array (
                'id' => 9,
                'name' => 'bbsmadminuser',
                'email' => 'bbsmadmin@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$3I8XovQqoN7bX41MB1.pT.VhCJrRzTqrW7VjkdQeuz1GNWqVi1SMq',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => '2019-04-17 20:31:10',
                'department_id' => 0,
            ),
            5 => 
            array (
                'id' => 10,
                'name' => 'manoj adhikari',
                'email' => 'manoj@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$g0WyMlbDH2kuwx6fv4QpqOFYTeuntqXu524uhv5oFfL2h/..kytX2',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => '16SrHYLI0t71u2XaoaXDBGWqJVASFgOlFSDqzm2TWVuc7tHxkUilg9fNx59V',
                'created_at' => '2019-04-17 20:32:42',
                'updated_at' => '2019-08-01 16:33:04',
                'department_id' => 0,
            ),
            6 => 
            array (
                'id' => 11,
                'name' => 'Manju Adhikari',
                'email' => 'manju@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$UxC0k3tkuBakDHZcOXuLK.TnA24Ya1VaiCIR0JgKT6KFv7XwYdcf2',
                'branch_id' => 1,
                'staff_central_id' => 151,
                'remember_token' => 'fnPUq0CGpqgzJ6RGGtldKrCFEVESmQM10yDpdghVwMVtkQrGtETbLLGwp5Yb',
                'created_at' => '2019-04-17 22:46:26',
                'updated_at' => '2019-07-19 19:46:23',
                'department_id' => 0,
            ),
            7 => 
            array (
                'id' => 13,
                'name' => 'Juna',
                'email' => 'junarana.pun@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$FPaF5r1L4npdWd.J5cg8HuU.lSGZjQaqFsQ2JDdPfCsl20F2vIft2',
                'branch_id' => 1,
                'staff_central_id' => 0,
                'remember_token' => 'pNYbvknR7BLWe5CveJS4EI7SeftRHKdGo612rCsuNDnQnvpogWo9mLOPSOwT',
                'created_at' => '2019-04-21 21:55:01',
                'updated_at' => '2019-04-23 22:39:19',
                'department_id' => 0,
            ),
            8 => 
            array (
                'id' => 14,
                'name' => 'Shree Sthapit',
                'email' => 'shree@bmpinfology.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$DCQeXB/daTBKD4m7FIigxOpJZuwq5DD00roSpuLo89iph7eoSeAYC',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => NULL,
                'created_at' => '2019-04-29 20:04:27',
                'updated_at' => '2019-04-29 20:04:27',
                'department_id' => 0,
            ),
            9 => 
            array (
                'id' => 15,
                'name' => 'sukragrg.sg@gmail.com',
                'email' => 'sukragrg.sg@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$sgVZ9jTWTMKtXtcZP.DwLebKrF6pEyGyvfW5D/ptXQExJYoZIrXgO',
                'branch_id' => 1,
                'staff_central_id' => 90,
                'remember_token' => 'm6DrVkEJX7EZh7UMmoJDZAMD3SfiRD4Lm8G6NkbsU5b5XbA8yKsfvDmZRpDi',
                'created_at' => '2019-04-30 13:54:13',
                'updated_at' => '2019-04-30 13:54:13',
                'department_id' => 0,
            ),
            10 => 
            array (
                'id' => 16,
                'name' => 'Grocery',
                'email' => 'grocery@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$qAXJxWVUTMacKy4fLyjF.eEHKFNja12y7n5FIH4tE13cnTdcOpGsm',
                'branch_id' => 1,
                'staff_central_id' => 30,
                'remember_token' => 'CS52CTA5jMscqmeJi8cSyMiOsZGbmH5wRmVKfLuGWtT9rgDYTKhnQs8YsAzl',
                'created_at' => '2019-05-15 04:01:30',
                'updated_at' => '2019-05-15 04:01:30',
                'department_id' => 0,
            ),
            11 => 
            array (
                'id' => 17,
                'name' => 'Kitchenware',
                'email' => 'kitchenware@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$XPw/rq5OdlXnCy/nDm/PF.2S/shWf8/Om6kWr/JwkHpzZuXaS9cf.',
                'branch_id' => 1,
                'staff_central_id' => 298,
                'remember_token' => 'VLIsIsujhxdyse99nxVpnuMIGBpQyxnz6k0MQo96D1OqEmMAbWgCBxHEi7Ml',
                'created_at' => '2019-05-15 04:03:07',
                'updated_at' => '2019-05-15 04:03:07',
                'department_id' => 0,
            ),
            12 => 
            array (
                'id' => 18,
                'name' => 'Shoes and Saree',
                'email' => 'shoessaree@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$xXiXqM51abmskpjmoyHOKe0SgmajIQUWltGkd0yo3QZ8YLbqNRyVC',
                'branch_id' => 1,
                'staff_central_id' => 15,
                'remember_token' => 'tvlmFqibq1UX5CInl52LsZWVkJsjd4NE1tHmEOfjzDyA07s7pZmvlVYwHPLY',
                'created_at' => '2019-05-15 04:04:17',
                'updated_at' => '2019-05-15 04:04:17',
                'department_id' => 0,
            ),
            13 => 
            array (
                'id' => 19,
                'name' => 'Kiran Rai',
                'email' => 'kiran@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$E/cIDjrmpULo86UG1KFHMuiA9RtBZAOz.gs/9d2wxBIPzKntDb6Sa',
                'branch_id' => 1,
                'staff_central_id' => 127,
                'remember_token' => 'Pg5HlJSpUhS3feBfcdeI8T2PFCQF5SzCvmxzzSw87HpLUIDTsGhbmaSxIWf4',
                'created_at' => '2019-05-24 00:20:26',
                'updated_at' => '2019-05-24 00:20:26',
                'department_id' => 0,
            ),
            14 => 
            array (
                'id' => 20,
                'name' => 'bijay',
                'email' => 'bijay@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$/ZjW0dxWLppzUqsalzZQ8OR87wy//1Dln8JmPzDwBSdu6rwSLQvam',
                'branch_id' => 1,
                'staff_central_id' => 346,
                'remember_token' => NULL,
                'created_at' => '2019-05-27 17:26:34',
                'updated_at' => '2019-05-27 17:26:34',
                'department_id' => 0,
            ),
            15 => 
            array (
                'id' => 21,
                'name' => 'prabhatkaucha',
                'email' => 'prabhat@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$7ArmSKVnxsety6lNRI..heWJThjdCM3HyzScwfNmoQemNTIbewt7C',
                'branch_id' => 1,
                'staff_central_id' => 75,
                'remember_token' => 'IMj13P1vZspJ7dHipmuQMn3ZloRkva1ZCaMp26Qq9VsYbhjwFvu55HBp2DUc',
                'created_at' => '2019-06-10 18:22:14',
                'updated_at' => '2019-06-10 18:22:14',
                'department_id' => 0,
            ),
            16 => 
            array (
                'id' => 22,
                'name' => 'sujalgurung',
                'email' => 'sujal@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$nGd/sZSv8xuWLvtxoA.OR.Sny2CV0.l00B7bX44KrB1CFo8qUfHVO',
                'branch_id' => 2,
                'staff_central_id' => 820,
                'remember_token' => 'QFlmONVDuueIzvfaoTxCLBGGYP2R3C7IoPcjz6EQskE25mxBkTNETqikdxld',
                'created_at' => '2019-06-14 17:05:27',
                'updated_at' => '2019-06-14 17:05:27',
                'department_id' => 0,
            ),
            17 => 
            array (
                'id' => 23,
                'name' => 'binashrestha',
                'email' => 'bina@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$ddGpxpqF3vY.JaI0zuByS.GD4QbAH1FSH7YEZhj8fakCx84EWyuty',
                'branch_id' => 2,
                'staff_central_id' => 852,
                'remember_token' => 'HrKxCSQaOPCiBrTT6kRRmTlGZgU1XEkqEijgmD0jCEyWfyouyLkUH2srnfnS',
                'created_at' => '2019-06-14 20:13:48',
                'updated_at' => '2019-06-14 20:13:48',
                'department_id' => 0,
            ),
            18 => 
            array (
                'id' => 24,
                'name' => 'sangitabudathoki',
                'email' => 'sangita@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$dCuqJzwGnfNddNp7IBK5zuutE7cSqVJVnOGloieDui0dtp9jNjssG',
                'branch_id' => 2,
                'staff_central_id' => 803,
                'remember_token' => 'LMZNekzvFfz7W7XiVEq0ORiLwXVFNNwwVfajr8YXZSLd2vWTgYidifZnryvX',
                'created_at' => '2019-06-14 20:35:00',
                'updated_at' => '2019-06-14 20:35:00',
                'department_id' => 0,
            ),
            19 => 
            array (
                'id' => 25,
                'name' => 'anilgurung',
                'email' => 'anil@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$ZexkXUNwbWtwtbz8pfNnae5tF9IfS8CAXiT/r6DWC1/AaLTWam3BO',
                'branch_id' => 3,
                'staff_central_id' => 1102,
                'remember_token' => 'VP2ZaqqmwGeCJJNOz1xlAQBGgc6ggK92QyS5i0k8rKHFazM3fhSUnugQ4CM4',
                'created_at' => '2019-07-10 18:45:19',
                'updated_at' => '2019-07-14 19:35:47',
                'department_id' => 0,
            ),
            20 => 
            array (
                'id' => 26,
                'name' => 'laxmishrestha',
                'email' => 'laxmi@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$c9etyM4adxGW8L9vWhj24uox5FJK8mhmpUQ9R0lprzY6LD8b6l3L.',
                'branch_id' => 3,
                'staff_central_id' => 1032,
                'remember_token' => 'Ux3U9kGQIrlLhFzYavwcG4Jp72refFDqupd9dBd0Seql4UK9emxm6yWmOOuO',
                'created_at' => '2019-07-10 18:47:32',
                'updated_at' => '2019-07-18 20:43:04',
                'department_id' => 0,
            ),
            21 => 
            array (
                'id' => 27,
                'name' => 'prajjwolkansakar',
                'email' => 'prajjwol@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$0.bNL.ePOhyeAdeW1817h.HPl5OHEPEwMgb2fAt592IeuakgyHoVC',
                'branch_id' => 2,
                'staff_central_id' => 1016,
                'remember_token' => NULL,
                'created_at' => '2019-07-15 20:50:11',
                'updated_at' => '2019-07-15 20:50:11',
                'department_id' => 0,
            ),
            22 => 
            array (
                'id' => 28,
                'name' => 'branch@bmpinfology.com',
                'email' => 'branch@bmpinfology.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$.UylDWTmnb9wbcGwBoVp2OzD83Zba7h6mRjZli7a.aiUtwxdY3xnq',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => 'JEijtdZAJIvWKFZBdoqINXVu9kW51ciCxZZtJYu0qUHewIMGakPPrv9qv21t',
                'created_at' => '2019-09-26 11:47:18',
                'updated_at' => '2019-09-26 11:47:18',
                'department_id' => 0,
            ),
            23 => 
            array (
                'id' => 29,
                'name' => 'ASHISH THAPA',
                'email' => 'ashish@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$7Nrq8.xoYVaOQfobl1kWC..QIIfAW1vpSxV.G0nxtazM01BgvBDAm',
                'branch_id' => 17,
                'staff_central_id' => 1553,
                'remember_token' => 'CiTgoxOhTlDtd5zGP8AQGED9FLVysGzcLjlMzFajp1ZfWkYyecJVV1sqx1fJ',
                'created_at' => '2019-10-18 09:45:48',
                'updated_at' => '2019-10-18 09:45:48',
                'department_id' => 0,
            ),
            24 => 
            array (
                'id' => 30,
                'name' => 'sanjeeta',
                'email' => 'sanjeeta@bbsm.com.np',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Zr2S6x5mRK4Lz5ufTJYJfeFtB8/7g3vrzTg20GroxVlQ0EW/Kf7Dq',
                'branch_id' => 1,
                'staff_central_id' => NULL,
                'remember_token' => 'ePyHqSeOAW6IqZzlBdrl5n4uXnrZetzCCS1B1pL17ViQsbd8cR91VX16QZIZ',
                'created_at' => '2019-10-18 15:37:51',
                'updated_at' => '2019-10-18 15:37:51',
                'department_id' => 0,
            ),
        ));
        
        
    }
}