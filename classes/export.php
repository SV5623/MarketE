<?php
// Ensure that the session is started
session_start();

// Include the PdoConnect class
require '../classes/PdoConnect.php';

function exportToCSV() {
    try {
        // Create a PDO connection
        $conn = PdoConnect::getInstance();

        // Open the CSV file for writing
        $csvFile = fopen('exported_data_allegro.csv', 'w');

        // CSV headers
        $csvHeaders = array(
            'ID produktu',
        'Kategoria główna',	
        'Podkategoria',	
        'Sygnatura/SKU Sprzedającego',	
        'Liczba sztuk',	
        'Cena',
        'Tytuł oferty',	
        'Zdjęcia',
        'Opis oferty',	
        'Cennik dostawy',	
        'Czas wysyłki',
        'Kraj',
        'Województwo',
        'Kod pocztowy',	
        'Miejscowość',
        'Opcje faktury',	
        'Przedmiot oferty',	
        'Stawki',
        'VAT',
        'Podstawa wyłączenia z VAT',	
        'Warunki zwrotów',
        'Warunki reklamacji',	
        'Informacje o gwarancjach (opcjonalne)',
        'Stan',
        'Liczba m² w ofercie [m²]',	
        'Stan opakowania',
        'Pojemność w ofercie [ml]',	
        'Liczba sztuk w opakowaniu [szt.]',	
        'Struktura',
        'Gama kolorystyczna',	
        'Wykończenie',
        'Jednostka sprzedaży',	
        'Marka',
        'Materiał',	
        'Długość [cm]',
        'Szerokość produktu [cm]',	
        'Grubość [mm]',
        'Kod producenta',	
        'Rodzaj',
        'Format',
        'Rodzaj impregnacji',	
        'Waga [kg]',
        'Opakowanie',
        'Kolor producenta',
        'Wielkość opakowania [kg]',	
        'Przeznaczenie',	
        'Krawędzie',	
        'Pojemność [l]',	
        'Wysokość produktu [cm]',	
        'Rodzaj produktu',	
        'Wysokość [mm]',	
        'Szerokość [mm]',	
        'Długość [m]',	
        'Kolor',	
        'Typ',	
        'Szerokość [m]',	
        'Stopień połysku',	
        'Wielkość opakowania [l]',	
        'Gramatura [g/m²]',	
        'Liczba m² w opakowaniu',
        'Kształt pędzla	Szerokość pędzla [cm]',	
        'Pojemność [ml]',
        'Opakowanie [m²]',	
        'Producent',	
        'Średnica [mm]',	
        'Szerokość wałka [cm]',	
        'Współczynnik izolacji cieplnej - Lambda [W/m²]',	
        'Ilość na rolce [m²]',
        'Waga produktu z opakowaniem jednostkowym [kg]',	
        'Waga płyty [kg]	Informacje dodatkowe',	
        'Maksymalna szerokość spoiny [mm]',
        'Kształt',	
        'Maksymalna wydajność z opakowania [m²]',	
        'Temperatura pracy [°C]', 	
        'Przybliżony czas wyschnięcia [h]',	
        'Liczba sztuk na m² [szt.]',
        'Maks. szerokość [cm]',
        'Zastosowanie',
        'Motyw',
        'Bohater',
        'Liczba sztuk [szt.]',	
        'Wzór',	
        'Rodzaje tynków',	
        'Rodzaj opakowania',	
        'Rodzaje cegieł',
        'Liczba sztuk [szt.]',	
        'Wydajność [m²/l]',
        'Czas schnięcia [h]',	
        'Sposób aplikacji',	
        'Efekt',
        'Funkcje',
        'Grubość [µm]',	
        'Deklarowana wydajność [m²/l]',	
        'Czas pełnego wyschnięcia [h]',	
        'Czas schnięcia w dotyku [h]',	
        'Czas pomiędzy położeniem drugiej warstwy [h]',	
        'Sposób rozcieńczania',	
        'Zawartość zestawu',
        'Gradacja',
        'Kolekcja',
        'Liczba warstw',	
        'Cechy dodatkowe',	
        'Orientacja',
        'Liczba elementów',	
        'Czas wiązania [h]',	
        'Maksymalny czas pełnego utwardzenia [h]',	
        'Zużycie [kg/m²/mm]',
        'Zastosowanie izolacji',	
        'Waga rolki [kg]',
        'Długość [mm]',	
        'Współczynnik izolacji cieplnej Lambda [W/mK]',	
        'Rodzaj farby',
        'Materiał poszycia',	
        'Czas schnięcia [min]', 	
        'Typ zastosowania',	
        'Temperatura aplikacji [°C]', 	
        'Sposób zamknięcia',
        'Frakcja [mm]',
            // Додайте інші заголовки за необхідності
        );

        fputcsv($csvFile, $csvHeaders);

        // SQL query to select data from your table
        $sql = "SELECT id, name, price, image, opis, kategoria, liczba_sztuk, kraj, kod_pocztowy, stan FROM goods";
        $stmt = $conn->PDO->prepare($sql);
        $stmt->execute();

        // Write data to the CSV file
        while ($row = $stmt->fetch()) {
            $csvData = array();

            // Map database columns to CSV headers
            foreach ($csvHeaders as $header) {
                switch ($header) {
                    case 'ID produktu':
                        $csvData[] = $row['id'];
                        break;
                    case 'Tytuł oferty':
                        $csvData[] = $row['name'];
                        break;
                    case 'Cena':
                        $csvData[] = $row['price'];
                        break;
                    case 'Zdjęcia':
                        $csvData[] = $row['image'];
                        break;
                    case 'Opis oferty':
                        $csvData[] = $row['opis'];
                        break;
                    case 'Kategoria':
                        $csvData[] = $row['kategoria'];
                        break;
                    case 'Liczba sztuk':
                        $csvData[] = $row['liczba_sztuk'];
                        break;
                    case 'Kraj':
                        $csvData[] = $row['kraj'];
                        break;
                    case 'Kod pocztowy':
                        $csvData[] = $row['kod_pocztowy'];
                        break;
                    case 'Stan':
                        $csvData[] = $row['stan'];
                        break;
                    default:
                        $csvData[] = ''; // Leave empty if the column doesn't exist in the database
                }
            }
            fputcsv($csvFile, $csvData);
        }

        // Close the CSV file
        fclose($csvFile);

        // Output a success message or download the file
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="exported_data_allegro.csv"');
        readfile('exported_data_allegro.csv');

    } catch (PDOException $e) {
        // Логуємо помилки або повідомляємо користувача
        error_log("Connection failed: " . $e->getMessage());
        echo "Something went wrong. Please try again later.";
    }
}

// Викликаємо функцію
exportToCSV();
?>
