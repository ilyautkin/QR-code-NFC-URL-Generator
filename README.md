# QRNFCGenerator - Generate QR Codes/NFC campaign URL's for your resources #
![QRNFCGenerator](https://img.shields.io/badge/version-1.0.1-brightgreen.svg) ![MODX Extra by Sterc](https://img.shields.io/badge/extra%20by-sterc-ff69b4.svg)  

QRNFCGenerator is an extra which allows you to generate QR codes and NFC URL's for a specific resource from the edit resource manager page and also supports Google Campaign Tracking.

**Note:** This extra only works for MODX3.

After installing the extra two buttons are added in the action menu of a resource edit manager page:
- Download QR Code
- Generate NFC Tag URL

Both URL's will have the following structure:
{resource_url}?utm_source={type}&utm_medium={type}&utm_campaign=website&qrnfc_res={encoded_resource_id}

## Dashboard Widget ##
A dashboard widget is installed where you'll be able to see the resource visit statistics compared between the current week versus the past week.

## System settings ##

The following system settings are available.

| Setting                      | Description                                                                                                                                                                                                                            |
|------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| qrnfcgenerator.utm_campaign  | Set the UTM Campaign Name you want to use for the generated QR Code and NFC Tag URL's.                                                                                                                                                 |

# Free Extra
This is a free extra and the code is publicly available for you to change. The extra is being actively maintained and you're free to put in pull requests which match our roadmap. Please create an issue if the pull request differs from the roadmap so we can make sure we're on the same page.

Need help? [Approach our support desk for paid premium support.](mailto:service@sterc.com)
