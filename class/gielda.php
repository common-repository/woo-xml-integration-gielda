<?php 

	class WCI_Gielda
	{

		static $transient_name_generated = 'gielda_xml_generated';

		protected $path;
		protected $daneXMLFilePath = '/assets/xml_gielda.xml';
		protected $daneXMLPath = 'http://twojagielda.com/wp-content/xmlfile/xml_gielda.xml';
		protected $data;
		protected $XmlArrayOfCategory;
			
		protected $gieldaGroups = array(
			'books' => array(
				'name' => 'Książki',
				'fields' => array(
					'Autor' => array(
							'title' => 'Imię i nazwisko autora',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'ISBN' => array(
							'title' => 'Kod ISBN nadawany książkom',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Ilosc_stron' => array(
							'title' => 'Informacja na temat liczby stron',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Wydawnictwo' => array(
							'title' => 'Nazwa wydawnictwa',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Rok_wydania' => array(
							'title' => 'Rok publikacji książki',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Oprawa' => array(
							'title' => 'Rodzaj oprawy np., miękka, twarda',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Format' => array(
							'title' => 'Wymiary książki np. B5, A5, 172x245cm, 15.5x22.5cm',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Spis_tresci' => array(
							'title' => 'Link do spisu treści',
							'type' => 'text',
							'label' => '',
							'default' => ''
					),
					'Fragment' => array(
							'title' => 'Link do fragmentu książki',
							'type' => 'text',
							'label' => '',
							'default' => ''
					)
				)
			),
			'tires' => array(
				'name' => 'Opony',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent opony',
							'type' => 'text',
							'label' => ''
					),
					'SAP' => array(
							'title' => 'Kod producenta',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Model' => array(
							'title' => 'Model opony',
							'type' => 'text',
							'label' => ''
					),
					'Szerokosc_opony' => array(
							'title' => 'Szerokość opony w milimetrach',
							'type' => 'text',
							'label' => ''
					),
					'Profil' => array(
							'title' => 'Profil opony',
							'type' => 'text',
							'label' => ''
					),
					'Srednica_kola' => array(
							'title' => 'Średnica osadzenia podana w calach',
							'type' => 'text',
							'label' => ''
					),
					'Indeks_predkosc' => array(
							'title' => 'Indeks dopuszczalnej prędkości',
							'type' => 'text',
							'label' => ''
					),
					'Indeks_nosnosc' => array(
							'title' => 'Maksymalne obciążenie w funtach',
							'type' => 'text',
							'label' => ''
					),
					'Sezon' => array(
							'title' => 'Sezonowość np. Zimowe, Letnie, Całoroczne',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'rims' => array(
				'name' => 'Felgi i kołpaki',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent perfum',
							'type' => 'text',
							'label' => ''
					),
					'Kod_producenta' => array(
							'title' => 'Kod nadawany produktowi przez producenta',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Rozmiar' => array(
							'title' => 'Szerokość i zewnętrzna średnica w calach np. 6,5x15',
							'type' => 'text',
							'label' => ''
					),
					'Rozstaw_srub' => array(
							'title' => 'Liczba śrub mocujących i średnica okręgu, na której znajdują się otwory np. 5x110',
							'type' => 'text',
							'label' => ''
					),
					'Odsadzenie' => array(
							'title' => '(tylko dla felg) Odległości między płaszczyzną montażową obręczy, a jej środkiem symetrii (ET)',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'perfumes' => array(
				'name' => 'Perfumy',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent perfum',
							'type' => 'text',
							'label' => ''
					),
					'Kod_producenta' => array(
							'title' => 'Kod nadawany produktowi przez producenta',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Linia' => array(
							'title' => 'Linia zapachu – seria np. Miss Pucci, Orange Celebration of Happiness',
							'type' => 'text',
							'label' => ''
					),
					'Rodzaj' => array(
							'title' => 'Rodzaj produktu np. Woda perfumowana, Woda toaletowa, Woda kolońska, Dezodorant roll on, Dezodorant sztyft',
							'type' => 'text',
							'label' => ''
					),
					'Pojemnosc' => array(
							'title' => 'Pojemność podana w mililitrach np. 50 ml, 100 ml',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'music' => array(
				'name' => 'Płyty muzyczne',
				'fields' => array(
					'Wykonawca' => array(
							'title' => 'Imię i nazwisko wykonawcy lub nazwa zespołu',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod kreskowy EAN',
							'type' => 'text',
							'label' => ''
					),
					'Nosnik' => array(
							'title' => 'Rodzaj nośnika np. DVD, CD',
							'type' => 'text',
							'label' => ''
					),
					'Wytwornia' => array(
							'title' => 'Nazwa wytwórni muzycznej',
							'type' => 'text',
							'label' => ''
					),
					'Gatunek' => array(
							'title' => 'Gatunek muzyczny',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'games' => array(
				'name' => 'Gry PC / Gry na konsole',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent gry',
							'type' => 'text',
							'label' => ''
					),
					'Kod_producenta' => array(
							'title' => 'Kod nadawany produktowi przez producenta',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod kreskowy EAN',
							'type' => 'text',
							'label' => ''
					),
					'Platforma' => array(
							'title' => 'Platforma, na jaką jest przeznaczona gra np. PC, PS2, Xbox360',
							'type' => 'text',
							'label' => ''
					),
					'Gatunek' => array(
							'title' => 'Gatunek gry np. Akcji, Wyścigi',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'movies' => array(
				'name' => 'Filmy',
				'fields' => array(
					'Rezyser' => array(
							'title' => 'Imię i nazwisko reżysera filmu',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Nosnik' => array(
							'title' => 'Rodzaj nośnika np. DVD, VCD, Blu-Ray',
							'type' => 'text',
							'label' => ''
					),
					'Wytwornia' => array(
							'title' => 'Nazwa wytwórni filmowej',
							'type' => 'text',
							'label' => ''
					),
					'Obsada' => array(
							'title' => 'Aktorzy grający w danym filmie',
							'type' => 'text',
							'label' => ''
					),
					'Tytul_oryginalny' => array(
							'title' => 'Oryginalny tytuł filmu',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'medicines' => array(
				'name' => 'Leki, suplementy',
				'fields' => array(
					'Producent' => array(
							'title' => 'Nazwa firmy farmaceutycznej',
							'type' => 'text',
							'label' => ''
					),
					'BLOZ_12' => array(
							'title' => 'Identyfikator leku – konieczne jest podanie minimum jednego z kodów dla leków i produktów aptecznych. Zalecane jest podawanie obydwu kodów dla każdego produktu. Dla artykułów, które nie posiadają kodu Bloz12 należy podać kod Bloz7.',
							'type' => 'text',
							'label' => ''
					),
					'BLOZ_7' => array(
							'title' => 'Identyfikator leku – konieczne jest podanie minimum jednego z kodów dla leków i produktów aptecznych. Zalecane jest podawanie obydwu kodów dla każdego produktu. Dla artykułów, które nie posiadają kodu Bloz12 należy podać kod Bloz7.',
							'type' => 'text',
							'label' => ''
					),
					'Ilosc' => array(
							'title' => 'Liczba tabletek, pojemność butelki np. 12szt., 250ml',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'grocery' => array(
				'name' => 'Delikatesy',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent produktu',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Ilosc' => array(
							'title' => 'Ilość w opakowaniu np.. 12szt., 2kg',
							'type' => 'text',
							'label' => ''
					)
				)
				
			),
			'clothes' => array(
				'name' => 'Odzież, obuwie, dodatki',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent produktu',
							'type' => 'text',
							'label' => ''
					),
					'Model' => array(
							'title' => 'Model produktu',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					),
					'Kolor' => array(
							'title' => 'Kolor dominujący, w przypadku gdy produkt występuje w kilku wariantach kolorystycznych powinna się pojawić oddzielna oferta dla każdego koloru (pole wymagane)',
							'type' => 'text',
							'label' => ''
					),
					'Rozmiar' => array(
							'title' => 'Rozmiar, w przypadku gdy produkt jest dostępny w różnych rozmiarach poszczególne wartości powinny zostać oddzielone średnikiem np. ‘S;L;XL’(pole wymagane)',
							'type' => 'text',
							'label' => ''
					),
					'Kod_produktu' => array(
							'title' => 'Kod nadawany przez producenta',
							'type' => 'text',
							'label' => ''
					),
					'Sezon' => array(
							'title' => 'Sezon np. ‘wiosna/lato’',
							'type' => 'text',
							'label' => ''
					),
					'Fason' => array(
							'title' => 'Rozumiany, jako fason pojedyncza wartość np. ‘rurki’, ‘dzwony’, ‘szerokie’, ‘szmizjerka’',
							'type' => 'text',
							'label' => ''
					),
					'ProductSetId' => array(
							'title' => 'Oznaczenie zestawu',
							'type' => 'text',
							'label' => ''
					)
				)
			),
			'other' => array( // must be last
				'name' => 'Inne',
				'fields' => array(
					'Producent' => array(
							'title' => 'Producent danego produktu',
							'type' => 'text',
							'label' => ''
					),
					'Kod_producenta' => array(
							'title' => 'Kod nadawany produktowi przez producenta',
							'type' => 'text',
							'label' => ''
					),
					'EAN' => array(
							'title' => 'Kod paskowy występujący na produktach, opakowaniach',
							'type' => 'text',
							'label' => ''
					)
				)
			)
		);
		
		public function __construct($path)
		{
			$this->path = $path;
		}
		
		public function getGieldaGroupsArray()
		{
			return $this->gieldaGroups;
		}

		/**
		 * Get XML file data
		 *
		 * @return string
		 */
		protected function getXmlData() {
		    $data = @file_get_contents( $this->daneXMLPath );
		    if ( empty( $data ) ) {
			    $data = file_get_contents(str_replace('/class', '', $this->path) . $this->daneXMLFilePath );
		    }
		    return trim( $data );
		}
		
		public function getCategoryWithFullName($categoryId)
		{
			if (empty($this->data))
			{
				$this->data = $this->getXmlData();
			}
			if (empty($this->XmlArrayOfCategory))
			{
				$this->XmlArrayOfCategory = new SimpleXMLElement( $this->data );
			}
			
			$categories = $this->XmlArrayOfCategory->xpath("//Id[.=$categoryId]/parent::*");
			return $categories[0]->Name;
	/*		$cName = array();
			while (!empty($categories[0]))
			{
				if (!empty($categories[0]->Name))
				{
					$cName[] = (string) $categories[0]->Name;
					
					// 2.4 only first element of category path
					//return implode('/', $cName);
				}
				$categories = $categories[0]->xpath("parent::*");
			}
			
			
			if (!empty($cName))
			{
				return implode('/', array_reverse($cName));
			} else {
				return '';
			}
			*/
						
		}
		
		public function getGieldaCategoryTreeHTMLOptionsArray()
		{
			//$XmlArrayOfCategory = new SimpleXMLElement( $dane = file_get_contents( $this->path . $this->daneXMLPath ) );
			if (empty($this->data))
			{
				$this->data = $this->getXmlData();
			}
			if (empty($this->XmlArrayOfCategory))
			{
				$this->XmlArrayOfCategory = new SimpleXMLElement( $this->data );
			}
			
			$options = $this->getGieldaCategoryTreeHTMLOptionsRecursive($this->XmlArrayOfCategory);
			return $options;
		}
		
		private function getGieldaCategoryTreeHTMLOptionsRecursive($xmlRoot, $parentNameArray = array())
		{
			$options = array();
			
			foreach ($xmlRoot->children() as $xmlCategory)
			{
				$options[(string) $xmlCategory->Id] = (string) $xmlCategory->Name;
				if (!empty($xmlCategory->Subcategories))
				{
					$options[(string) $xmlCategory->Id . 'group'] = implode(' - ', array_merge($parentNameArray, array((string) $xmlCategory->Name)));
					$options = $options + $this->getGieldaCategoryTreeHTMLOptionsRecursive($xmlCategory->Subcategories, $parentNameArray + array((string) $xmlCategory->Name));
				}
			}
			return $options;
		}
	}