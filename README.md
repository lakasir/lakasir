<div align="center">

  <img src="https://lakasir.com/assets/logo/image.png" alt="logo" width="200" height="auto" />
  <h1>Lakasir Web App</h1>

  <p> Lakasir is a Point of Sale (POS) application built using Laravel for the API, the Filament admin panel for the web application, and Flutter for the mobile application. </p>
  
</div>

## Requirements
- **Docker** (v20.10 or later) - [Get Docker](https://docs.docker.com/get-docker/)
- **Docker Compose** (v2.0 or later) - [Install Compose](https://docs.docker.com/compose/install/)
- **Make** (v4.3 or later) - Available in most UNIX-based systems or install via [GNU Make](https://www.gnu.org/software/make/)

> **Note**: PHP 8.2 and Node.js 20+ are optional and only required if developers want to make changes to the application.


## Features
- **Role Management**: Define roles and permissions for users.
- **Transaction Management**: Handle sales transactions seamlessly.
- **Product Management**: Manage your inventory and products effectively.
- **Unit Price**: Different pricing based on units.
- **Discount**: Apply discounts per item or global discounts.
- **Purchasing**: Manage purchase orders and suppliers.
- **Stock Opname**: Conduct stock taking and inventory audits.
- **Receivable Management**: Track and manage receivables.
- **Payment Method Management**: Manage various payment methods.
- **Voucher Management**: Create, distribute, and track vouchers.
- **Reporting**: Generate sales and performance reports.
- **Simple Accounting**: Track income, expenses, and profits.
- **Real-time Dashboard**: Monitor metrics in real-time.
- **Web USB Direct Printing**: Support for thermal printers via browser USB features.
- **Barcode Support**: Use barcodes in stock opname, purchasing, and POS features.

## Screenshots

<div style="display:inline-block" align="center">
  <img src="./readme/Screenshot/cashier-menu.png" alt="Product Detail" width="400" />
  &emsp;
  <img src="./readme/Screenshot/product-detail.png" alt="Product Detail" width="400"/>  
</div>
<!-- ![Lakasir Screenshot](./readme/Screenshot/product-detail.png) -->

## Technologies Used
* **Backend**: [Laravel](https://laravel.com)
* **Frontend** (Web): [Filament Admin Panel](https://filamentphp.com)
* **Frontend** (Mobile): [Flutter](https://flutter.github.io)

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/jahrulnr/lakasir.git
cd lakasir
```

### 2. Setup Environment Variables
Copy the example environment file and modify it as needed:
```bash
cp .env.example .env
```

### 3. Build and Run the Application
We use **Makefile** for easier management. Below are the steps to set up and run the application:

1. **Build the Docker Image**:
```bash
make build
```

2. **Start the Containers**:
```bash
make run
```

3. **Run Database Container Only** (Optional):
```bash
make run-db
```

4. **Run Database Migrations and Seed Data**:
```bash
make install
```

---

## Usage

- API Endpoint: `http://localhost/api/test`
- Web App: `http://localhost/member/login`

---

## Contributing

Contributions are welcome! Follow these steps:
1. Fork the repository.
2. Create a new branch:
```bash
git checkout -b feature/new-feature
```
3. Make your changes and commit:
```bash
git commit -am 'Add new feature'
```
4. Push to the branch:
```bash
git push origin feature/new-feature
```
5. Create a Pull Request.

---

## License

This project is licensed under the GPL-3.0 license. See the [LICENSE](LICENSE) file for details.

---

## Contact

For support, contact lakasirapp@gmail.com or open a discussion on GitHub.



## Donate for live longer

[<img src="https://trakteer.id/images/v2/trakteer-logo.png" alt="drawing" width="100"/>](https://trakteer.id/sheenazien8/tip?quantity=1)
<a href="https://www.buymeacoffee.com/sheenazien8" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-green.png" alt="Buy Me A Coffee" style="height: 20px !important;width: 100px !important;" ></a>


## Star History

<a href="https://star-history.com/#lakasir/lakasir&Date">
 <picture>
   <source media="(prefers-color-scheme: dark)" srcset="https://api.star-history.com/svg?repos=lakasir/lakasir&type=Date&theme=dark" />
   <source media="(prefers-color-scheme: light)" srcset="https://api.star-history.com/svg?repos=lakasir/lakasir&type=Date" />
   <img alt="Star History Chart" src="https://api.star-history.com/svg?repos=lakasir/lakasir&type=Date" />
 </picture>
</a>
