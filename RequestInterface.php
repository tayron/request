<?php

namespace Tayron;

/**
 * Interface que define implementação de uma classe Request
 *
 * @author Tayron Miranda <dev@tayron.com.br>
 */
interface RequestInterface 
{
    /**
     * Request::redirect
     *
     * Método que redireciona para uma outra tela
     *
     * @param string $location Local para onde se deve redirecionar
     * @return void
     */
    public function redirect($location);
    
    /**
     * Request::getUri
     *
     * Método que retorna uma lista de parametros informados na URI
     *
     * @return array Lista de parametros da requisições uri
     */
    public function getUri();

    /**
     * Request::getPostParameter
     *
     * Metodo que retorna os parametros enviados via POST
     *
     * @param string $key Nome do parametro a ser recuperado
     * @return mixed Valor aramazenado via POST
     */
    public function getPostParameter($key = null);

    /**
     * Request::getGetParameter
     *
     * Metodo que retorna os parametros enviados via GET
     *
     * @param string $key Nome do parametro a ser recuperado
     * @return mixed Valor aramazenado via GET
     */
    public function getGetParameter($key = null);
    
    /**
     * Request::getPutParameter
     *
     * Metodo que retorna os parametros enviados via PUT
     *
     * @param string $key Nome do parametro a ser recuperado
     * @return mixed Valor aramazenado via PUT
     */    
    public function getPutParameter($key = null);

    /**
     * Request::url
     *
     * Método que monta a retorna url para uma determinada pagina
     *
     * @param string $location Nome do controller + action podendo ter parametros via get
     * @return string Retorna url completa com o link informado
     */
    public function url($location = null);

    /**
     * Request::isPost
     *
     * Método que informa se a requisição feita foi via POST
     *
     * @return boolean Retorna true caso a requisição seja feita via POST
     */
    public function isPost();

    /**
     * Request::isGet
     *
     * Método que informa se a requisição feita foi via GET
     *
     * @return boolean Retorna true caso a requisição seja feita via GET
     */
    public function isGet();
    
    /**
     * Request::isDelete
     *
     * Método que informa se a requisição feita foi via DELETE
     *
     * @return boolean Retorna true caso a requisição seja feita via DELETE
     */
    public function isDelete();
    
    /**
     * Request::isPut
     *
     * Método que informa se a requisição feita foi via PUT
     *
     * @return boolean Retorna true caso a requisição seja feita via PUT
     */
    public function isPut();
}
