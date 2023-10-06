$(document).ready(function() {
    $('#dodaj-jezyk').click(function() {
      var jezykiContainer = $('#jezyki-container');
      var liczbaJezykow = jezykiContainer.find('.jezyk').length + 1;
      
      var nowyJezyk = $('<hr class="line-style-3">' +
                        '<div class="jezyk">' +
                        '<label for="jezyk' + liczbaJezykow + '">Język:</label>' +
                        '<select id="jezyk' + liczbaJezykow + '" name="jezyki[' + (liczbaJezykow - 1) + '][nazwa]" required>' +
                        '<option disabled selected value="">Wybierz język</option>' +
                        '<option value="angielski">Angielski</option>' +
                        '<option value="niemiecki">Niemiecki</option>' +
                        '</select>' +
                        '<label for="poziom' + liczbaJezykow + '">Poziom:</label>' +
                        '<select id="poziom' + liczbaJezykow + '" name="jezyki[' + (liczbaJezykow - 1) + '][poziom]" required>' +
                        '<option disabled selected value="">Wybierz poziom</option>' +
                        '<option value="podstawowy">Podstawowy</option>' +
                        '<option value="średniozaawansowany">Średniozaawansowany</option>' +
                        '<option value="zaawansowany">Zaawansowany</option>' +
                        '</select>' +
                        '</div>');
                      
                      jezykiContainer.append(nowyJezyk);
                    });
                    });

$(document).ready(function() {
    $('#dodaj-wyksztalcenie').click(function() {
        var wyksztalcenieContainer = $('#wyksztalcenie-container');
        var liczbaWyksztalcen = wyksztalcenieContainer.find('.wyksztalcenie').length + 1;
                        
        var noweWyksztalcenie = $('<hr class="line-style-3">' +
                          '<div class="wyksztalcenie">' +
                          '<label for="wyksztalcenie' + liczbaWyksztalcen + '">Nazwa szkoły/uczelni:</label>' +
                          '<input type="text" id="wyksztalcenie' + liczbaWyksztalcen + '" name="wyksztalcenia[' + (liczbaWyksztalcen - 1) + '][name]" placeholder="Wprowadź nazwę szkoły/uczelni">' +
                          '<label for="wyksztalcenie-info1' + liczbaWyksztalcen + '">Wprowadź nazwę kierunku/specjalności:</label>' +
                          '<input type="text" id="wyksztalcenie-info1' + liczbaWyksztalcen + '" name="wyksztalcenia[' + (liczbaWyksztalcen - 1) + '][kierunek]" placeholder="Wprowadź nazwę kierunku/specjalności">' +
                          '<label for="poziom-wyk1' + liczbaWyksztalcen + '">Poziom:</label>' +
                          '<select id="poziom-wyk1' + liczbaWyksztalcen + '" name="wyksztalcenia[' + (liczbaWyksztalcen - 1) + '][level]"' +
                          '<option selected value="">Wybierz poziom wykształcenia</option>' +
                          '<option disabled selected value="">Wybierz tytuł</option>' +
                          '<option value="podstawowe">Podstawowe</option>' +
                          '<option value="średnie">Średnie</option>' +
                          '<option value="wyższe">Wyższe</option>' +
                          '<option value="podyplomowe">Podyplomowe</option>' +
                          '<option value="ustawiczne">Ustawiczne</option>' +
                          '</select>' +
                          '<label for="tytul-wyk1' + liczbaWyksztalcen + '">Tytuł:</label>' +
                          '<select id="tytul-wyk1' + liczbaWyksztalcen + '" name="wyksztalcenia[' + (liczbaWyksztalcen - 1) + '][title]">' +
                          '<option disabled selected value="">Wybierz tytuł</option>' +
                          '<option value="brak">Brak</option>' +
                          '<option value="licencjat">Licencjat</option>' +
                          '<option value="inżynier">Inżynier</option>' +
                          '<option value="magister">Magister</option>' +
                          '<option value="doktor">Doktor</option>' +
                          '<option value="doktor habilitowany">Doktor habilitowany</option>' +
                          '<option value="profesor">Profesor</option>' +
                          '</select>' +
                          '</div>');
                                        
                          wyksztalcenieContainer.append(noweWyksztalcenie);
                          });
                          });