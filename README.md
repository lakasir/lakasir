# Lakasir
Lakasir is a Point of Sale (POS) application built using Laravel for the API, the Filament admin panel for the web application, and Flutter for the mobile application.

## Features
Role Management: Define roles and permissions for users.
Transaction Management: Handle sales transactions seamlessly.
Product Management: Manage your inventory and products effectively.
Reporting: Generate reports for insights into sales and performance.
Simple Accounting: Basic accounting features for financial tracking.

## Technologies Used
* **Backend**: [Laravel](https://laravel.com)
* **Frontend** (Web): [Filament Admin Panel](https://filamentphp.com)
* **Frontend** (Mobile): [Flutter](https://flutter.github.io)

## Installation
1. Clone the repository: git clone https://github.com/lakasir/lakasir.git
2. Navigate to the project directory: cd lakasir
3. Install dependencies:
4. Laravel:
   * composer install
   * cp .env.example .env
   * edit the env based on your local configuration
   * **!IMPORTANT** APP_CENTRAL_DOMAINS=base on your local domain
   * php artisan migrate
   * create the merchant,
     1. using API:
        ```
        POST /api/domain/register HTTP/1.1
        Host: lakasir.test
        Accept: application/json
        Content-Type: application/json
        Content-Length: 142
        {
            "full_name": "",
            "domain": "",
            "email": "",
            "password": "",
            "password_confirmation": "",
            "business_type": "retail"
        }
      2. using tinker
         ```PHP
            $data = [
                'name' => 'toko_test',
                'domain' => 'toko_test.'.config('tenancy.central_domains')[0],
                'email' => 'toko_test@mail.com',
                'password' => 'password',
                'full_name' => 'Toko Test',
                'shop_name' => 'Toko Test',
                'business_type' => 'Retail',
            ];
            $tenant = Tenant::create([
                'id' => 'toko_test',
                'tenancy_db_name' => 'lakasir_tenancy_toko_test',
            ]);
            $tenant->domains()->create([
                'domain' => $data['domain'],
            ]);
            $tenant->user()->create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $tenant->user->about()->create([
                'shop_name' => $data['shop_name'],
                'business_type' => $data['business_type'],
            ]);
        3. php artisan tenants:seed


## Contributing

We welcome contributions from the community! If you'd like to contribute to Lakasir, please follow these steps:

1. keep on eye on [project board](https://github.com/orgs/lakasir/projects/2/views/1)
2. Fork the repository.
3. Create a new branch (git checkout -b feature/new-feature). 
4. Make your changes and commit them (git commit -am 'Add new feature').
5. Push to the branch (git push origin feature/new-feature).
6. Create a new Pull Request.
   
When contributing to this project, please keep an eye on our project features board on GitHub to stay updated with ongoing and planned features.

## License
This project is licensed under the GPL-3.0 license - see the [LICENSE](https://github.com/lakasir/lakasir?tab=GPL-3.0-1-ov-file) file for details.

## Contact
For any inquiries or support, please contact lakasirapp@gmail.com or you can open discussion in discussion features


