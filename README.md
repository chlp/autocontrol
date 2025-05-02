# Vehicle Checkpoint Automation System

This project provides a standalone PHP-based application that automates a specific vehicle checkpoint scenario. It implements business logic for vehicle entry/exit through gates, integrated with physical control systems and external services.

## 📁 Project Structure

- `php/` — main application source code
- `php/Config.php` — application configuration (database credentials, service addresses, etc.)
- `php/mysql.sql` — MySQL database schema and sample data

## 🚦 Features

- Standalone web-based control system for a vehicle checkpoint
- Business logic written in **PHP**
- Uses its own **MySQL** database
- Integrates with:
  - Relay boards for **sensor and barrier control**
  - **Microsoft Navision** for business process verification
  - **License Plate Recognition (LPR)** software
  - **Weighing equipment** via **COM port** - use the application https://github.com/chlp/mosbrew_scales

## ⚙️ Setup Instructions

- [Instruction.md](./Instruction.md)