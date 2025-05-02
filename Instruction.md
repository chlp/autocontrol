# Auto-Control Software

![autocontrol](https://github.com/user-attachments/assets/66bb13b5-070d-4014-b4cc-18eb2a4f052e)

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

![diagram-1](https://github.com/user-attachments/assets/19d13423-04dc-4d62-8950-6885397bc8dd)

Diagram 1 shows the main nodes the Auto-Control server interacts with. It helps explain the standard flow of a vehicle passing through the checkpoint.

1. A vehicle arrives at the external side of the plant's checkpoint. The driver steps out and hands over a delivery note.
2. The checkpoint operator scans the note at the operator's workstation.
3. Auto-Control reads the document number and queries Navision. If the document is invalid or incomplete, an error is returned. If valid, Navision indicates whether it's an entry or exit.
4. Auto-Control enables the entry barrier control button. The barrier is operated via a relay board. The operator opens the barrier and the vehicle enters.
5. The vehicle drives onto the scales connected to the operator's PC. Both barriers lock. Auto-Control receives the weight data and license plate number.
6. The operator fills in the required form and submits it. Auto-Control validates the data with Navision. If errors are found, correction is required. If unresolvable, the process is canceled and the vehicle reverses. If everything is valid, the exit barrier button is enabled and the vehicle leaves. The cycle ends.

## Network Diagram

Diagram 2

![diagram-2](https://github.com/user-attachments/assets/5de669c4-181b-4847-88ca-fc4c648a6162)

Diagram 2 shows the system layout from Diagram 1 in more detail, including addresses, hostnames, and data exchange ports.

## Barrier Control Loop

Diagram 3

![diagram-3](https://github.com/user-attachments/assets/d47143dc-daf2-46c8-b63c-7b365c443eb7)

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

<img width="872" alt="image" src="https://github.com/user-attachments/assets/cf3cfda3-3b1d-4d48-ac3e-decf03538c83" />

<img width="919" alt="image" src="https://github.com/user-attachments/assets/2f4525fb-d733-4dda-b6ba-1e0bab55886f" />

<img width="449" alt="image" src="https://github.com/user-attachments/assets/e44b0d2f-ab3b-4f59-adda-48a00877dc94" />

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

<img width="1329" alt="image" src="https://github.com/user-attachments/assets/809f3962-27ad-448a-9c5a-eecc4e6ba59d" />

Set IIS to run under a user with access to Navision (e.g., `NAV_KPP`). Deploy to `C:\inetpub\wwwroot`. Config file path: `C:\inetpub\wwwroot\Config.php`.

![iis-1](https://github.com/user-attachments/assets/998aad91-51dc-44cd-8181-0fe77c634c61)

<img width="650" alt="iis-2" src="https://github.com/user-attachments/assets/302ce306-a90b-4d7b-9822-f20a139661bd" />

<img width="646" alt="iis-3" src="https://github.com/user-attachments/assets/abb814e9-8bbb-4faa-89c0-4f8cf45914f3" />

<img width="645" alt="iis-4" src="https://github.com/user-attachments/assets/eb8166ca-f92a-4391-944c-e217cb15df56" />

<img width="626" alt="iis-5" src="https://github.com/user-attachments/assets/5c5397f8-f76e-4862-a55e-445099595bb3" />

<img width="628" alt="iis-6" src="https://github.com/user-attachments/assets/e2a02b33-7fe2-41be-af03-ca3559ea0d27" />

<img width="693" alt="iis-7" src="https://github.com/user-attachments/assets/2ec857ff-ce45-4159-8764-edab64428c1f" />

<img width="750" alt="iis-8" src="https://github.com/user-attachments/assets/d7546211-3e3c-4bff-abb3-7528a4e10da8" />

<img width="771" alt="iis-9" src="https://github.com/user-attachments/assets/0a52ea97-a8ec-4b24-8ea1-999fc0ace181" />

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

<img width="1192" alt="client" src="https://github.com/user-attachments/assets/4ea73d71-8582-41e1-abe2-a24f19ad8ac4" />
