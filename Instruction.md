# Auto-Control Software

<img width="874" alt="image" src="https://github.com/user-attachments/assets/66bb13b5-070d-4014-b4cc-18eb2a4f052e" />

* [**Overview**](#overview)
* * [Purpose](#purpose)
* * [Vehicle Checkpoint Workflow](#vehicle-checkpoint-workflow)
* * [Network Diagram](#network-diagram)
* * [Barrier Control Loop](#barrier-control-loop)
* [**Server Installation and Deployment**](#server-installation-and-deployment)
* * [Software Overview](#software-overview)
* * [Quick Installation Guide](#quick-installation-guide)
* * [MySQL Setup](#mysql-setup)
* * [IIS Configuration](#iis-configuration)
* * [License Plate Recognition Setup](#license-plate-recognition-setup)
* [**Operator Workstation Setup**](#operator-workstation-setup)

# Overview

## Purpose

Auto-Control is a network-based software system that manages vehicle traffic passing through checkpoint gates. It interacts with corporate databases, network services (informational), and physical systems such as barriers and scales. The software provides access to its functionality through a web interface.

## Vehicle Checkpoint Workflow

Diagram 1

Diagram 1 shows the main nodes the Auto-Control server interacts with. It helps explain the standard flow of a vehicle passing through the checkpoint.

1. A vehicle arrives at the external side of the plant's checkpoint. The driver steps out and hands over a delivery note.
2. The checkpoint operator scans the note at the operator's workstation.
3. Auto-Control reads the document number and queries Navision. If the document is invalid or incomplete, an error is returned. If valid, Navision indicates whether it's an entry or exit.
4. Auto-Control enables the entry barrier control button. The barrier is operated via a relay board. The operator opens the barrier and the vehicle enters.
5. The vehicle drives onto the scales connected to the operator's PC. Both barriers lock. Auto-Control receives the weight data and license plate number.
6. The operator fills in the required form and submits it. Auto-Control validates the data with Navision. If errors are found, correction is required. If unresolvable, the process is canceled and the vehicle reverses. If everything is valid, the exit barrier button is enabled and the vehicle leaves. The cycle ends.

## Network Diagram

Diagram 2

Diagram 2 shows the system layout from Diagram 1 in more detail, including addresses, hostnames, and data exchange ports.

## Barrier Control Loop

Diagram 3

Barriers are operated via relay boards. A physical circuit is closed to trigger movement (open/close). By controlling relay outputs, the system can allow or restrict access. Diagram 3 shows the interaction clearly.

# Server Installation and Deployment

## Software Overview

Auto-Control runs on Windows, uses IIS (Internet Information Server), PHP, and MySQL. It communicates with external servers using PostgreSQL and MSSQL modules.

## Quick Installation Guide

Install MySQL 5.x, PHP 7.3 (with curl, mbstring, mysqli, pgsql, sqlsrv), configure IIS. Unpack the PHP code into the web root and restore the database from the dump.

Program:
- [Download PHP code](./php)

Database:
- [Download MySQL dump](./php/mysql.sql)

Edit `Config.php` in the root directory to configure the system.

## MySQL Setup

Tested with MySQL 5.7.29 Community Edition. Download:  
[mysql-5.7.29-winx64.zip](https://downloads.mysql.com/archives/get/p/23/file/mysql-5.7.29-winx64.zip)

Only install the Server component and restore the database from the dump.

## IIS Configuration

Download and install PHP (7.3 NTS x64) and required extensions:

- [PHP 7.3.22 NTS x64](https://windows.php.net/downloads/releases/php-7.3.22-nts-Win32-VC15-x64.zip)
- [ODBC Driver 17 for SQL Server](https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server)
- [ODBC Driver 11](https://www.microsoft.com/en-us/download/confirmation.aspx?id=36434)
- [sqlsrv.dll](https://pecl.php.net/package/sqlsrv/5.8.1/windows)
- [IIS URL Rewrite](https://www.iis.net/downloads/microsoft/url-rewrite)

Edit `php.ini` and enable:

```
extension=curl
extension=mbstring
extension=mysqli
extension=pgsql
extension=php_sqlsrv.dll
```

Set IIS to run under a user with access to Navision (e.g., `NAV_KPP`). Deploy to `C:\inetpub\wwwroot`. Config file path: `C:\inetpub\wwwroot\Config.php`.

## License Plate Recognition Setup

The recognition server may block new addresses. Add them to `pg_hba.conf` on the LPR server:

```
host all all 10.1.3.121/32 md5
```

## Operator Workstation Setup

Install [mosbrew_scales](https://github.com/chlp/mosbrew_scales) for COM port weight reading and HTTP forwarding.

Copy the shortcut to `go-mosbrew-scales.exe` into `%userprofile%\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup` for auto-launch.

Use Chromium or Ungoogled Chromium:
- [ungoogled-chromium Win64](https://chromium.woolyss.com/)